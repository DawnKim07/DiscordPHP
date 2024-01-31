<?php

namespace Discord\AutoModeration;

use Psr;
use React;
require "../vendor/autoload.php";

class Rule{
    public string $id, $guild_id, $name, $creator_id;
    public int $event_type, $trigger_type;
    public object $trigger_metadata;
    public array $actions, $exempt_roles, $exempt_channels;
    public bool $enabled;

    public function list_auto_moderation_rules(string $guild_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/auto-moderation/rules";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            return $data;
        });
    }

    public function list_auto_moderation_rule(string $guild_id, string $auto_moderation_rule_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/auto-moderation/rules/"
        . $auto_moderation_rule_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "guild_id", "name", "creator_id", "event_type", "trigger_type", "trigger_metadata",
            "actions", "exempt_roles", "exempt_channels", "enabled"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    private function event_type_converter(string $event_type){
        $array = [
            "MESSAGE_SEND" => 1
        ];

        if(isset($array[$event_type])){
            $return = $array[$event_type];
            return $return;
        } else {
            return Null;
        }
    }

    private function trigger_type_converter(string $trigger_type){
        $array = [
            "KEYWORD" => 1,
            "SPAM" => 3,
            "KEYWORD_PRESET" => 4,
            "MENTION_SPAM" => 5
        ];

        if(isset($array[$trigger_type])){
            $return = $array[$trigger_type];
            return $return;
        } else {
            return Null;
        }
    }

    public function create_auto_moderation_rule(string $guild_id, ?string $audit_log_reason,
    string $name, int|string $event_type, int|string $trigger_type,
    ?\Discord\AutoModeration\Rule\TriggerMetadata $trigger_metadata, array $actions,
    ?bool $enabled, ?array $exempt_roles, ?array $exempt_channels){

        if(gettype($event_type)=="string"){
            $event_type = $this->event_type_converter($event_type);
        }

        if(gettype($trigger_type)=="string"){
            $trigger_type = $this->trigger_type_converter($trigger_type);
        }

        $data = [
            "name" => $name,
            "event_type" => $event_type,
            "trigger_type" => $trigger_type,
            "actions" => $actions
        ];

        $dataList = [
            "trigger_metadata" => $trigger_metadata,
            "enabled" => $enabled,
            "exempt_roles" => $exempt_roles,
            "exempt_channels" => $exempt_channels
        ];
        $dataKeyList = ["trigger_metadata", "enabled", "exempt_roles", "exempt_channels"];

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
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/auto-moderation/rules";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "guild_id", "name", "creator_id", "event_type", "trigger_type", "trigger_metadata",
            "actions", "enabled", "exempt_roles", "exempt_channels"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function modify_auto_moderation_rule(string $guild_id, string $auto_moderation_rule_id, 
    ?string $audit_log_reason, ?string $name, int|string $event_type=Null, 
    ?\Discord\AutoModeration\Rule\TriggerMetadata $trigger_metadata,
    ?array $actions, ?bool $enabled, ?array $exempt_roles, ?array $exempt_channels){

        if(gettype($event_type)=="string"){
            $event_type = $this->event_type_converter($event_type);
        }

        $data = [];

        $dataList = [
            "name" => $name,
            "event_type" => $event_type,
            "actions" => $actions,
            "trigger_metadata" => $trigger_metadata,
            "enabled" => $enabled,
            "exempt_roles" => $exempt_roles,
            "exempt_channels" => $exempt_channels
        ];
        $dataKeyList = ["name", "event_type", "actions",
        "trigger_metadata", "enabled", "exempt_roles", "exempt_channels"];

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
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/auto-moderation/rules/"
        . $auto_moderation_rule_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "guild_id", "name", "creator_id", "event_type", "trigger_type", "trigger_metadata",
            "actions", "enabled", "exempt_roles", "exempt_channels"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function delete_auto_moderation_rule(string $guild_id, string $auto_moderation_rule_id,
    ?string $audit_log_reason){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/auto-moderation/rules/"
        . $auto_moderation_rule_id;
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