<?php

namespace Discord;

use Psr;
use React;
require "../vendor/autoload.php";

class GuildScheduledEvent{

    public string $id, $guild_id, $channel_id, $creator_id, $name, $description, $scheduled_start_time,
    $scheduled_end_time, $entity_id, $image;
    public int $privacy_level, $event_status, $entity_type, $user_count;
    public object $entity_metadata, $creator;

    private function privacy_level_converter(string $level){
        $array = [
            "GUILD_ONLY" => 2
        ];

        if(isset($array[$level])){
            $value = $array[$level];
            return $value;
        } else {
            return Null;
        };
    }

    private function event_status_converter(string $type){
        $array = [
            "SCHEDULED" => 1,
            "ACTIVE" => 2,
            "COMPLETED" => 3,
            "CANCELED" => 4
        ];

        if(isset($array[$type])){
            $value = $array[$type];
            return $value;
        } else {
            return Null;
        };
    }

    private function entity_type_converter(string $type){
        $array = [
            "STAGE_INSTANCE" => 1,
            "VOICE" => 2,
            "EXTERNAL" => 3
        ];

        if(isset($array[$type])){
            $value = $array[$type];
            return $value;
        } else {
            return Null;
        };
    }

    public function list_scheduled_events(string $guild_id, ?bool $with_user_count){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        if(isset($with_user_count)){
            $query = "?with_user_count=" . "$with_user_count";
        } else {
            $query = "";
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/scheduled-events" . $query;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "guild_id", "channel_id", "creator_id", "name", "description",
            "scheduled_start_time", "scheduled_end_time", "privacy_level", "status", "entity_type",
            "entity_id", "entity_metadata", "creator", "user_count", "image"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function create_guild_scheduled_event(string $guild_id, ?string $audit_log_reason,
    ?string $channel_id, ?\Discord\GuildScheduledEvent\EntityMetadata $entity_metadata,
    string $name, string|int $privacy_level=Null, string $scheduled_start_time, ?string $scheduled_end_time,
    ?string $description, string|int $entity_type=Null, ?\Discord\DataFormat\Image $image){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        if(gettype($privacy_level) == "string"){
            $privacy_level = $this->privacy_level_converter($privacy_level);
        }

        if(gettype($entity_type) == "string"){
            $entity_type = $this->entity_type_converter($entity_type);
        }

        $data = [
            "name" => $name,
            "privacy_level" => $privacy_level,
            "scheduled_start_time" => $scheduled_start_time,
            "entity_type" => $entity_type
        ];

        $dataList = [
            "channel_id" => $channel_id,
            "entity_metadata" => $entity_metadata,
            "scheduled_end_time" => $scheduled_end_time,
            "description" => $description,
            "image" => $image
        ];
        $dataKeyList = ["channel_id", "entity_metadata", "scheduled_end_time", "description", "image"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/scheduled-events";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "guild_id", "channel_id", "creator_id", "name", "description",
            "scheduled_start_time", "scheduled_end_time", "privacy_level", "status", "entity_type",
            "entity_id", "entity_metadata", "creator", "user_count", "image"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function get_guild_scheduled_event(string $guild_id, string $guild_scheduled_event_id,
    ?bool $with_user_count){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        if(isset($with_user_count)){
            $query = "?with_user_count=" . "$with_user_count";
        } else {
            $query = "";
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/scheduled-events/"
         . $guild_scheduled_event_id . $query;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "guild_id", "channel_id", "creator_id", "name", "description",
            "scheduled_start_time", "scheduled_end_time", "privacy_level", "status", "entity_type",
            "entity_id", "entity_metadata", "creator", "user_count", "image"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function modify_guild_scheduled_event(string $guild_id, string $guild_scheduled_event_id,
    ?string $audit_log_reason, ?string $channel_id, ?\Discord\GuildScheduledEvent\EntityMetadata $entity_metadata,
    ?string $name, string|int $privacy_level=Null, ?string $scheduled_start_time, ?string $scheduled_end_time,
    ?string $description, string|int $entity_type=Null, string|int $status=Null,
    ?\Discord\DataFormat\Image $image){

        if(gettype($privacy_level) == "string"){
            $privacy_level = $this->privacy_level_converter($privacy_level);
        }

        if(gettype($entity_type) == "string"){
            $entity_type = $this->entity_type_converter($entity_type);
        }

        if(gettype($status) == "string"){
            $status = $this->event_status_converter($status);
        }

        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [];

        $dataList = [
            "channel_id" => $channel_id,
            "entity_metadata" => $entity_metadata,
            "name" => $name,
            "privacy_level" => $privacy_level,
            "scheduled_start_time" => $scheduled_start_time,
            "scheduled_end_time" => $scheduled_end_time,
            "description" => $description,
            "entity_type" => $entity_type,
            "status" => $status,
            "image" => $image
        ];
        $dataKeyList = ["channel_id", "entity_metadata", "name",  "privacy_level", 
        "scheduled_start_time", "scheduled_end_time", "description", "entity_type",
        "status", "image"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/scheduled-events/"
        . $guild_scheduled_event_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "guild_id", "channel_id", "creator_id", "name", "description",
            "scheduled_start_time", "scheduled_end_time", "privacy_level", "status", "entity_type",
            "entity_id", "entity_metadata", "creator", "user_count", "image"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function delete_guild_scheduled_event(string $guild_id, string $guild_scheduled_event_id){

        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/scheduled-events/"
        . $guild_scheduled_event_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });

    }

}

?>