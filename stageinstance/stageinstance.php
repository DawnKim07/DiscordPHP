<?php

namespace Discord;

use Psr;
use React;
require "../vendor/autoload.php";

class StageInstance{
    public string $id, $guild_id, $channel_id, $topic, $guild_scheduled_event_id;
    public int $privacy_level;
    public bool $descoverable_disabled;

    private function privacy_level_converter(string $privacy_level){

        $array = [
            "PUBLIC" => 1,
            "GUILD_ONLY" => 2
        ];

        if(isset($array[$privacy_level])){
            $return = $array[$privacy_level];
            return $return;
        } else {
            return Null;
        }

    }

    public function create_stage_instance(?string $audit_log_reason, string $channel_id,
    string $topic, int|string $privacy_level=2, ?bool $send_start_notification, ?string $guild_scheduled_event_id){

        if(gettype($privacy_level) == "string"){
            $privacy_level = $this->privacy_level_converter($privacy_level);
        }

        $data = [
            "channel_id" => $channel_id,
            "topic" => $topic
        ];

        $dataList = [
            "privacy_level" => $privacy_level,
            "send_start_notification" => $send_start_notification,
            "guild_scheduled_event_id" => $guild_scheduled_event_id
        ];
        $dataKeyList = ["privacy_level", "send_start_notification", "guild_scheduled_event_id"];

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
        $url = "https://discord.com/api/v10/stage-instances";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "guild_id", "channel_id", "topic", "privacy_level",
            "discoverable_disabled", "guild_scheduled_event_id"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function get_stage_instance(string $channel_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/stage-instances/" . $channel_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "guild_id", "channel_id", "topic", "privacy_level",
            "discoverable_disabled", "guild_scheduled_event_id"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function modify_stage_instance(string $channel_id, ?string $audit_log_reason,
    ?string $topic, int|string $privacy_level=Null){

        if(gettype($privacy_level) == "string"){
            $privacy_level = $this->privacy_level_converter($privacy_level);
        }

        $data = [

        ];

        $dataList = [
            "topic" => $topic,
            "privacy_level" => $privacy_level
        ];
        $dataKeyList = ["topic", "privacy_level"];

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
        $url = "https://discord.com/api/v10/stage-instances/" . $channel_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "guild_id", "channel_id", "topic", "privacy_level",
            "discoverable_disabled", "guild_scheduled_event_id"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function delete_stage_instance(string $channel_id, ?string $audit_log_reason){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/stage-instances/" . $channel_id;
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