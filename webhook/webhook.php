<?php

namespace Discord;

use Psr;
use React;
require "../vendor/autoload.php";

class Webhook{
    public string $id, $guild_id, $channel_id, $name, $avatar, $token, $application_id, $url;
    public int $type;
    public object $user, $source_guild, $source_channel;

    private function webhook_type_converter(string $name){
        $array = [
            "Incoming" => 1,
            "Channel Follower" => 2,
            "Application" => 3
        ];

        if(isset($array[$name])){
            $return = $array[$name];
            return $return;
        } else {
            return Null;
        }
    }

    public function create_webhook(string $channel_id, ?string $audit_log_reason, string $name,
    ?\Discord\DataFormat\Image $avatar){

        $data = [
            "name" => $name
        ];

        if(isset($avatar)){
            $addData = [
                "avatar" => $avatar
            ];
            $data = array_merge($data, $addData);
        }

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/webhooks";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "channel_id", "user", "name", "avatar",
            "token", "application_id", "source_guild", "source_channel", "url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function get_channel_webhooks(string $channel_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/webhooks";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            return $data;
        });
    }

    public function get_guild_webhooks(string $guild_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/webhooks";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            return $data;
        });
    }

    public function get_webhook(string $webhook_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/webhooks/" . $webhook_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "channel_id", "user", "name", "avatar",
            "token", "application_id", "source_guild", "source_channel", "url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function get_webhook_with_token(string $webhook_id, string $webhook_token){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/webhooks/" . $webhook_id . "/" . $webhook_token;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "channel_id", "user", "name", "avatar",
            "token", "application_id", "source_guild", "source_channel", "url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function modify_webhook(string $webhook_id, ?string $audit_log_reason,
    ?string $name, ?\Discord\DataFormat\Image $avatar, ?string $channel_id){

        $data = [];

        $dataList = [
            "name" => $name,
            "avatar" => $avatar,
            "channel_id" => $channel_id
        ];
        $dataKeyList = ["name", "avatar", "channel_id"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/webhooks/" . $webhook_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason
        ];

        $browser->patch($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "channel_id", "user", "name", "avatar",
            "token", "application_id", "source_guild", "source_channel", "url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function modify_webhook_with_token(string $webhook_id, string $webhook_token,
    ?string $audit_log_reason, ?string $name, ?\Discord\DataFormat\Image $avatar){

        $data = [];

        $dataList = [
            "name" => $name,
            "avatar" => $avatar
        ];
        $dataKeyList = ["name", "avatar"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/webhooks/" . $webhook_id . "/" . $webhook_token;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason
        ];

        $browser->patch($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "channel_id", "user", "name", "avatar",
            "token", "application_id", "source_guild", "source_channel", "url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function delete_webhook(string $webhook_id, ?string $audit_log_reason){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/webhooks/" . $webhook_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason
        ];

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function delete_webhook_with_token(string $webhook_id, string $webhook_token,
    ?string $audit_log_reason){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/webhooks/" . $webhook_id . "/" . $webhook_token;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason
        ];

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    

}

?>