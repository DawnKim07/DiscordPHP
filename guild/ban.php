<?php

namespace Discord\Guild;

require "../vendor/autoload.php";
use Psr;
use React;

class Ban{
    public string $reason;
    public object $user;

    public function get_guild_bans(string $guild_id, ?int $limit=1000, ?string $before=Null, 
    ?string $after=Null){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $query = "?limit=" . "$limit" . "&before=" . "$before" . "&after=" . "$after";

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/bans" . $query;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            return $data;
        });
    }

    public function get_guild_ban(string $guild_id, string $user_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/bans/" . $user_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $code = $response->getStatusCode();
            if($code==404){
                return $code;
            }
            return $data;
        });
    }

    public function create_guild_ban(string $guild_id, string $user_id, ?string $audit_log_reason,
    ?int $delete_message_days=0, ?int $delete_message_seconds=0){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [];

        $dataList = [
            "delete_message_days" => $delete_message_days,
            "delete_message_seconds" => $delete_message_seconds
        ];
        $dataKeyList = ["delete_message_days", "delete_message_seconds"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/bans/" . $user_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];

        $browser->put($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function remove_guild_ban(string $guild_id, string $user_id, ?string $audit_log_reason){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/bans/" . $user_id;
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