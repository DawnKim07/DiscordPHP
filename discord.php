<?php
use React\EventLoop\Loop;

require __DIR__ . '/vendor/autoload.php';

class Discord{
    private $HBInterval, $Seq, $WSSUrl_RE, $SessionID, $timerLinear, $data_OP_1, $data_OP_2, $data_OP_6, $ReConn, $init;
    public $EventData, $EventName;

    public array $EventQueue, $EventCall;

    # 이벤트 등록 함수
    public function OnEvent($event, $callback){
        $this->EventQueue[$event] = $event;
        $this->EventCall[$event] = $callback;
    }

    private function EventPass($EventName, $data){
        $execute = $this->EventCall[$EventName];
        $execute($data);
    }

    # 봇 시작 함수
    public function run(){
        $loader = new Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $this->init($token);
    }

    # HeartBeat의 시작 메인함수
    private function init($token){
        $this->init = True;
        # API Gateway에 연결
        $client = new React\Http\Browser();
        
        $client->get(
            $url='https://discord.com/api/v10/gateway/bot',
            $headers=$this->header($token)
        )->then(function(Psr\Http\Message\ResponseInterface $response) use($token){
            echo '[Client] Connected to Discord HTTP API' . PHP_EOL;
            # Response에서 URL 캐시: 첫번째 캐시
            $responseJson = json_decode($response->getBody());
            $WSSUrl = $responseJson->url . '?v=10&encoding=json';
            $this->WSSConnect($token, $WSSUrl);
        }, function(Exception $e){
            echo '[Client] HTTP Connection Failed with Error: ' . $e->getMessage() . PHP_EOL;
        });
    }    
    
    # Websocket API에 최초 연결
    private function WSSConnect($token, $WSSUrl){
        $this->ReConn = False;

        $ReactTcpConnector = new React\Socket\TcpConnector();
        $dnsResolver = new React\Dns\Resolver\Factory();
        $dns = $dnsResolver->createCached('8.8.8.8');
        $ReactDnsConnector = new React\Socket\DnsConnector($ReactTcpConnector, $dns);
        $ReactSecureConnector = new React\Socket\SecureConnector($ReactDnsConnector, null, array(
            'crypto_method' => (STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_0_CLIENT)
        ));
        $loop = Loop::get();
        $connector = new Ratchet\Client\Connector($loop, $ReactSecureConnector);

        $connector($WSSUrl, [], $this->header($token))->then(function(Ratchet\Client\WebSocket $connection) use($token){
            echo '[Client] Connected to Discord WebSocket API' . PHP_EOL;
            
            $this->Seq = Null;
            $this->data_OP_1 = [
                "op" => 1,
                "d" => $this->Seq
            ];
            $this->data_OP_2 = [
                "op" => 2,
                "d" => [
                    "token" => "Bot" . " " . $token,
                    "intents" => 3276799,
                    "properties" => [
                        "os" => "windows",
                        "browser" => "StudyCafe",
                        "device" => "BotHost"
                    ]
                ]
            ];

            $loop = Loop::get();
            $connection->on('message', function(Ratchet\RFC6455\Messaging\MessageInterface $data) use($connection, $loop, $token){
                $dataArray = json_decode($data);
                $OPCode = $dataArray->op;
                switch($OPCode) {
                    case 10:
                        echo '[Client] Received Hello Event' . PHP_EOL;
                        $this->HBInterval = $dataArray->d->heartbeat_interval * (0.001);
                        $Jitter = mt_rand() / mt_getrandmax();
                        $this->timerLinear = $loop->addTimer((int)($this->HBInterval * $Jitter), function() use($connection){
                            echo '[Client] Sent HeartBeat Request' . PHP_EOL;
                            $connection->send(json_encode($this->data_OP_1));
                        });
                        echo '[Client] Sent Identify Request' . PHP_EOL;
                        $connection->send(json_encode($this->data_OP_2));
                        break;
                    case 1:
                        echo '[Warning] Received HeartBeat Request' . PHP_EOL;
                        $loop->cancelTimer($this->timerLinear);
                        $connection->send(json_encode($this->data_OP_1));
                        break;
                    case 11:
                        echo '[Client] Received HeartBeat Response' . PHP_EOL;
                        $this->timerLinear = $loop->addTimer($this->HBInterval, function() use($connection){
                            echo '[Client] Sent HeartBeat Request' . PHP_EOL;
                            $connection->send(json_encode($this->data_OP_1));
                        });
                        break;
                    case 0:
                        $this->EventData = $dataArray; 
                        $this->Seq = $dataArray->s;
                        $this->EventName = $dataArray->t;
                        echo '[Client] Received an Event: ' . $this->EventName . PHP_EOL;
                        if($this->init){
                            $this->WSSUrl_RE = $dataArray->d->resume_gateway_url . '?v=10&encoding=json';
                            $this->SessionID = $dataArray->d->session_id;
                            $this->init = False;
                        }

                        if(isset($this->EventName)){
                            if(in_array($this->EventName, $this->EventQueue)){
                                $this->EventPass($this->EventName, $this->EventData);
                            }
                            $this->EventName = Null;
                            $this->EventData = Null;
                        }
                        break;
                    case 7:
                        echo '[Warning] Connection Lost (OP Code: 7)' . PHP_EOL;
                        $this->ReConn = True;
                        $loop->cancelTimer($this->timerLinear);
                        $connection->close();
                        break;
                    case 9:
                        echo '[Warning] Connection Lost (OP Code: 9)' . PHP_EOL;
                        if($dataArray->d){
                            $this->ReConn = True;
                            $loop->cancelTimer($this->timerLinear);
                        } else {
                            $this->ReConn = False;
                        }
                        $loop->cancelTimer($this->timerLinear);
                        $connection->close();
                        break;
                    default:
                        echo '[Error] Connection Lost (OP Code: Unknown)' . PHP_EOL;
                        $this->ReConn = False;
                        $loop->cancelTimer($this->timerLinear);
                        $connection->close();
                }
            });

            $connection->on('error', function(Exception $e){
                $ErrorCode = $e->getCode();
                echo '[Error] Error Occured with ErrorCode: ' . $ErrorCode . PHP_EOL;
            });

            $connection->on('close', function($code, $reason) use($token){
                $loader = new Discord\ConfigLoad();
                $loader->Load();
                $token = $loader->token;

                echo '[Warning] WSS Connection Closed with Code: ' . $code . PHP_EOL;
                echo '[Warning] WSS Close reason: ' . $reason . PHP_EOL;

                if($code>=4000 and $code<=4009 and $code!=4004){
                    echo '[Client] Reconnecting using the same connector...' . PHP_EOL;
                    $this->ReConn = True;
                } else if($code==4004 or ($code>=4010 and $code<=4014)) {
                    $this->ReConn = False;
                }

                return new React\Promise\Promise(function() use($token){
                    if($this->ReConn == False) {
                        echo '[Client] Initiate Retrying...' . PHP_EOL;
                        $this->init($token);
                    } else if($this->ReConn == True){
                        echo '[Client] Reconnecting using the same connector...' . PHP_EOL;
                        $this->WSSReConnect($token);
                    }
                });

            });

        }, function(Throwable $e){
            echo '[Error] WSS Connection Failed: ' . $e->getMessage() . PHP_EOL;
        });

    }

    private function WSSReConnect($token){
        $this->ReConn = False;

        $ReactTcpConnector = new React\Socket\TcpConnector();
        $dnsResolver = new React\Dns\Resolver\Factory();
        $dns = $dnsResolver->createCached('8.8.8.8');
        $ReactDnsConnector = new React\Socket\DnsConnector($ReactTcpConnector, $dns);
        $ReactSecureConnector = new React\Socket\SecureConnector($ReactDnsConnector, null, array(
            'crypto_method' => (STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_0_CLIENT | STREAM_CRYPTO_METHOD_TLS_CLIENT)
        ));
        $loop = Loop::get();
        $connector = new Ratchet\Client\Connector($loop, $ReactSecureConnector);
        
        $connector($this->WSSUrl_RE, [], $this->header($token))->then(function(Ratchet\Client\WebSocket $connection) use($token){
            $this->data_OP_1 = [
                "op" => 1,
                "d" => $this->Seq
            ];
            
            $this->data_OP_6 = [
                "op" => 6,
                "d" => [
                    "token" => 'Bot' . ' ' . $token,
                    "session_id" => $this->SessionID,
                    "seq" => $this->Seq
                ]
            ];
            
            $loop = Loop::get();

            echo '[Client] Reconnected to Discord WebSocket API' . PHP_EOL;

            $connection->on('message', function(Ratchet\RFC6455\Messaging\MessageInterface $data) use($connection , $loop, $token){
                $dataArray = json_decode($data);
                $OPCode = $dataArray->op;
                switch($OPCode) {
                    case 10:
                        echo '[Client] Received Hello Event' . PHP_EOL;
                        $this->HBInterval = $dataArray->d->heartbeat_interval * (0.001);
                        $Jitter = mt_rand() / mt_getrandmax();
                        echo '[Client] Sent Connection Resume Request' . PHP_EOL;
                        $connection->send(json_encode($this->data_OP_6));
                        
                        $this->timerLinear = $loop->addTimer((int)($this->HBInterval * $Jitter), function() use($connection){
                            echo '[Client] Sent HeartBeat Request' . PHP_EOL;
                            $connection->send(json_encode($this->data_OP_1));
                        });
                        break;
                    case 1:
                        echo '[Info] Received HeartBeat Request' . PHP_EOL;
                        $loop->cancelTimer($this->timerLinear);
                        $connection->send(json_encode($this->data_OP_1));
                        break;
                    case 11:
                        echo '[Client] Received HeartBeat Response' . PHP_EOL;
                        $this->timerLinear = $loop->addTimer($this->HBInterval, function() use($connection){
                            echo '[Client] Sent HeartBeat Request' . PHP_EOL;
                            $connection->send(json_encode($this->data_OP_1));
                        });
                        break;
                    case 0:
                        $this->EventData = $dataArray; 
                        $this->Seq = $dataArray->s;
                        $this->EventName = $dataArray->t;
                        echo '[Client] Received an Event: ' . $this->EventName . PHP_EOL;

                        if(isset($this->EventName)){
                            if(in_array($this->EventName, $this->EventQueue)){
                                $this->EventPass($this->EventName, $this->EventData);
                            }
                            $this->EventName = Null;
                            $this->EventData = Null;
                        }
                        break;
                    case 7:
                        echo '[Warning] Connection Lost (OP Code: 7)' . PHP_EOL;
                        $this->ReConn = True;
                        $loop->cancelTimer($this->timerLinear);
                        $connection->close();
                        break;
                    case 9:
                        echo '[Warning] Connection Lost (OP Code: 9)' . PHP_EOL;
                        if($dataArray->d){
                            $this->ReConn = True;
                            $loop->cancelTimer($this->timerLinear);
                            $connection->close();
                        } else {
                            $this->ReConn = False;
                            $loop->cancelTimer($this->timerLinear);
                            $connection->close();
                        }
                        break;
                    default:
                        echo '[Error] Connection Lost (OP Code: Unknown)' . PHP_EOL;
                        $this->ReConn = False;
                        $loop->cancelTimer($this->timerLinear);
                        $connection->close();
                }
            });

            $connection->on('error', function(Exception $e){
                $ErrorCode = $e->getCode();
                echo '[Error] Error Occured with ErrorCode: ' . $ErrorCode . PHP_EOL;
            });

            $connection->on('close', function($code, $reason) use($token){
                echo '[Warning] WSS Connection Closed with Code: ' . $code . PHP_EOL;
                echo '[Warning] WSS Close reason: ' . $reason . PHP_EOL;

                if($code>=4000 and $code<=4009 and $code!=4004){
                    echo '[Client] Reconnecting using the same connector...' . PHP_EOL;
                    $this->ReConn = True;
                } else if($code==4004 or ($code>=4010 and $code<=4014)) {
                    $this->ReConn = False;
                }

                return new React\Promise\Promise(function() use($token){
                    if($this->ReConn == False) {
                        echo '[Client] Initiate Retrying...' . PHP_EOL;
                        $this->init($token);
                    } else if($this->ReConn == True){
                        echo '[Client] Reconnecting using the same connector...' . PHP_EOL;
                        $this->WSSReConnect($token);
                    }
                });

            }, function(Throwable $e){
                echo '[Error] WSS Connection Failed: ' . $e->getMessage() . PHP_EOL;
            });
        });
        
    }

    # HeartBeat의 인증이 필요한 HTTP 헤더 함수
    private function header($token){
        $header = [
            'Authorization' => 'Bot' . ' ' . $token
        ];
        return $header;
    }
}

?>