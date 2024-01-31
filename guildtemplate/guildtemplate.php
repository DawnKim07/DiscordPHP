<?php

namespace Discord;

use Psr;
use React;
require "../vendor/autoload.php";

class GuildTemplate{

    public string $code, $name, $description, $creator_id, $created_at, $updated_at, $source_guild_id;
    public int $usage_count;
    public object $creator, $serialized_source_guild;
    public bool $is_dirty;

    public function get_guild_template(string $template_code){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/templates/" . $template_code;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];
        
        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["code", "name", "description", "creator_id", "created_at", "updated_at",
            "source_guild_id", "usage_count", "creator", "serialized_source_guild", "is_dirty"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    
    }

    public function get_guild_templates(string $guild_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/templates";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];
        
        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            return $data;
        });
    }

    public function create_guild_template(string $guild_id, string $name, ?string $description){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [
            "name" => $name
        ];

        if(isset($description)){
            $addData = [
                "description" => $description
            ];
            $data = array_merge($data, $addData);
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/templates";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            return $data;
        });
    }

    public function sync_guild_template(string $guild_id, string $template_code){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/templates/" . $template_code;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];
        
        $browser->put($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["code", "name", "description", "creator_id", "created_at", "updated_at",
            "source_guild_id", "usage_count", "creator", "serialized_source_guild", "is_dirty"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function modify_guild_template(string $guild_id, string $template_code,
    ?string $name, ?string $description){
        $data = [];

        $dataList = [
            "name" => $name,
            "description" => $description
        ];
        $dataKeyList = ["name", "description"];

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
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/templates/" . $template_code;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["code", "name", "description", "creator_id", "created_at", "updated_at",
            "source_guild_id", "usage_count", "creator", "serialized_source_guild", "is_dirty"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function delete_guild_template(string $guild_id, string $template_code){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/templates/" . $template_code;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["code", "name", "description", "creator_id", "created_at", "updated_at",
            "source_guild_id", "usage_count", "creator", "serialized_source_guild", "is_dirty"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

}

?>