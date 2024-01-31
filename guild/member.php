<?php

namespace Discord\Guild;

require "../../vendor/autoload.php";
use Psr;
use React;

class Member{
    public object $user;
    public string $nick, $avatar, $roles, $joined_at, $permissions, $communication_disabled_until;
    public bool $deaf, $mute, $pending;
    public int $flags;

    public function get_guild_member(string $guild_id, string $user_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/members/" . $user_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["user", "nick", "avatar", "roles", "joined_at", "permissions",
            "communication_disabled_until", "deaf", "mute", "pending", "flags"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function list_guild_members(string $guild_id, ?int $limit=1, ?string $after="0"){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $query = "?limit=" . "$limit" . "&after=" . "$after";

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/members" . $query;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["user", "nick", "avatar", "roles", "joined_at", "permissions",
            "communication_disabled_until", "deaf", "mute", "pending", "flags"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function search_guild_members(string $guild_id, string $query, ?int $limit=1){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $query = "?query=" . "$query" . "&limit=" . "$limit";

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/members/search" . $query;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["user", "nick", "avatar", "roles", "joined_at", "permissions",
            "communication_disabled_until", "deaf", "mute", "pending", "flags"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function add_guild_member(string $guild_id, string $user_id, string $access_token,
    ?string $nick, ?array $roles, ?bool $mute, ?bool $deaf){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [
            "access_token" => $access_token
        ];

        $dataList = [
            "nick" => $nick,
            "roles" => $roles,
            "mute" => $mute,
            "deaf" => $deaf
        ];
        $dataKeyList = ["nick", "roles", "mute", "deaf"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/members/" . $user_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->put($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["user", "nick", "avatar", "roles", "joined_at", "permissions",
            "communication_disabled_until", "deaf", "mute", "pending", "flags"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }

            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function modify_guild_member(string $guild_id, string $user_id, ?string $audit_log_reason,
    ?string $nick, ?array $roles, ?bool $mute, ?bool $deaf, ?string $channel_id,
    ?string $communication_disabled_until, ?int $flags){

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [];

        $dataList = [
            "nick" => $nick,
            "roles" => $roles,
            "mute" => $mute,
            "deaf" => $deaf,
            "channel_id" => $channel_id,
            "communication_disabled_until" => $communication_disabled_until,
            "flags" => $flags
        ];
        $dataKeyList = ["nick", "roles", "mute", "deaf", "channel_id", "communication_disabled_until",
        "flags"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/members/" . $user_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["user", "nick", "avatar", "roles", "joined_at", "permissions",
            "communication_disabled_until", "deaf", "mute", "pending", "flags"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }

            $code = $response->getStatusCode();
            return $code;
        });

    }

    public function modify_current_member(string $guild_id, ?string $audit_log_reason, ?string $nick){

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [];

        $dataList = [
            "nick" => $nick
        ];
        $dataKeyList = ["nick"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/members/@me";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["user", "nick", "avatar", "roles", "joined_at", "permissions",
            "communication_disabled_until", "deaf", "mute", "pending", "flags"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }

            $code = $response->getStatusCode();
            return $code;
        });

    }

    public function add_guild_member_role(string $guild_id, string $user_id, string $role_id,
    ?string $audit_log_reason){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/members/" . $user_id . "/roles/" . $role_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason
        ];

        $browser->put($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function remove_guild_member_role(string $guild_id, string $user_id, string $role_id,
    ?string $audit_log_reason){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/members/" . $user_id . "/roles/" . $role_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason
        ];

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function remove_guild_member(string $guild_id, string $user_id,
    ?string $audit_log_reason){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/members/" . $user_id;
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