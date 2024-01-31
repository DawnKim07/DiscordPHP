<?php

namespace Discord\Channel;

use React;
use Psr;
require "../vendor/autoload.php";

class ArchivedThreads{

    public object $threads, $members;
    public bool $has_more;

    public function list_archived_threads(string $channel_id, string $accessible, ?string $before, ?int $limit){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        if($accessible !=("public" or "private")){
            return Null;
        }

        $def_array = [];

        $array = [
            "before" => $before,
            "limit" => $limit
        ];
        $arrayKeyList = ["before", "limit"];
        foreach($arrayKeyList as $arrayKey){
            if(isset($array[$arrayKey])){
                $newArray = [
                    $arrayKey => $array[$arrayKey]
                ];
                $def_array = array_merge($def_array, $newArray);
            }
        }

        $query = http_build_query($array);
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/threads/archived/" . $accessible . "?" . $query;

        $browser = new React\Http\Browser();
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());

            $this->threads = $data->threads;
            $this->members = $data->members;
            $this->has_more = $data->has_more;

            return $data;
        });
    }

    public function list_bot_joined_private_archived_threads(string $channel_id, ?string $before, ?int $limit){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $def_array = [];

        $array = [
            "before" => $before,
            "limit" => $limit
        ];
        $arrayKeyList = ["before", "limit"];
        foreach($arrayKeyList as $arrayKey){
            if(isset($array[$arrayKey])){
                $newArray = [
                    $arrayKey => $array[$arrayKey]
                ];
                $def_array = array_merge($def_array, $newArray);
            }
        }

        $query = http_build_query($array);
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/users/@me/threads/archived/private?" . $query;

        $browser = new React\Http\Browser();
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());

            $this->threads = $data->threads;
            $this->members = $data->members;
            $this->has_more = $data->has_more;

            return $data;
        });
    }

}

?>