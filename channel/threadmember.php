<?php

namespace Discord\Channel;

use React;
use Psr;

require "../vendor/autoload.php";

class ThreadMember{

    public string $id, $user_id, $join_timestamp;
    public int $flags;
    public object $member;

    public function get_thread_member(string $channel_id, string $user_id, ?bool $with_member){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        if(isset($with_member)){
            $query = http_build_query([
                "with_member" => $with_member
            ]);
            $url = "https://discord.com/api/v10/channels/" . $channel_id . "/thread-members/" . $user_id . "?" . $query;
        } else {
            $url = "https://discord.com/api/v10/channels/" . $channel_id . "/thread-members/" . $user_id;
        }

        $browser = new React\Http\Browser();
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());

            $keyList =["id", "user_id", "join_timestamp", "flags", "member"];
            foreach($keyList as $key){
                if(isset($data->$key)){
                    $this->$key = $data->$key;
                } else {
                    $this->$key = Null;
                }
            }
        });
    }

    public function list_thread_members(string $channel_id, ?bool $with_member,
    ?string $after, ?int $limit=100, ?int $index){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $def_array = [
            "limit" => $limit
        ];

        $array = [
            "with_member" => $with_member,
            "after" => $after
        ];
        $arrayKeyList = ["with_member", "after"];
        foreach($arrayKeyList as $arrayKey){
            if(isset($array[$arrayKey])){
                $newArray = [
                    $arrayKey => $array[$arrayKey]
                ];
                $def_array = array_merge($def_array, $newArray);
            }
        }

        $query = http_build_query($array);
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/thread-members?" . $query;

        $browser = new React\Http\Browser();
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response) use($index){
            $data = json_decode($response->getBody());

            if($index == Null){
                return $data;
            } else {
                $data = $data[$index];

                $keyList =["id", "user_id", "join_timestamp", "flags", "member"];
                foreach($keyList as $key){
                    if(isset($data->$key)){
                        $this->$key = $data->$key;
                    } else {
                        $this->$key = Null;
                    }
                }

                return $data;
            }
        });
    }

}

?>