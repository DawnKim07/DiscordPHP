<?php

namespace Discord;

use React;
use Psr;
require __DIR__ . '../../vendor/autoload.php';

class Emoji{
    
    public string $id, $name;
    public array $role, $emoji_list;
    public object $user;
    public Emoji $emoji;
    public bool $require_colons, $managed, $animated, $available;

    private array $emoji_for_components;

    public function set_for_components(string $name, string $id, bool $animated){
        $emoji = [
            "name" => $name,
            "id" => $id,
            "animated" => $animated
        ];

        $this->emoji_for_components = $emoji;
    }

    public function set_emoji_for_components(){
        if(!isset($this->emoji_for_components)){
            $this->emoji_for_components = Null;
        }

        return $this->emoji_for_components;
    }

    public function list_guild_emojis(string $guild_id, ?int $index){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
    
        $browser = new \React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $guild_id . "/emojis";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];

        $browser->get($url, $header)->then(function (Psr\Http\Message\ResponseInterface $response) use($index){
            $data = json_decode($response->getBody());
            $this->emoji_list = $data;
            $this->emoji = $data->$index;
        });
    }

    public function get_guild_emoji(string $guild_id, string $emoji_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
    
        $browser = new \React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $guild_id . "/emojis/" . $emoji_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];

        $browser->get($url, $header)->then(function (Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "name", "roles", "user", "required_colons", "managed",
            "animated", "available"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function create_guild_emoji(string $guild_id, string $name, string $audit_log_reason, \Discord\DataFormat\Image $image, array $roles){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
    
        $browser = new \React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $guild_id . "/emojis";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $data = [
            "name" => $name,
            "image" => $image,
            "roles" => $roles
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function (Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "name", "roles", "user", "required_colons", "managed",
            "animated", "available"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function modify_guild_emoji(string $guild_id, string $emoji_id, string $audit_log_reason, string $name, array $roles){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
    
        $browser = new \React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $guild_id . "/emojis/" . $emoji_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $data = [
            "name" => $name,
            "roles" => $roles
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function (Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "name", "roles", "user", "required_colons", "managed",
            "animated", "available"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function delete_guild_emoji(string $guild_id, string $emoji_id, string $audit_log_reason){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
    
        $browser = new \React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $guild_id . "/emojis/" . $emoji_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];

        $browser->delete($url, $header)->then(function (Psr\Http\Message\ResponseInterface $response){
            $data = $response->getStatusCode();
            return $data;
        });
    }

}

?>