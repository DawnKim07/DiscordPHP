<?php

namespace Discord\Guild;

use Psr;
use React;
require "../vendor/autoload.php";

class GuildOnboarding{
    public string $guild_id;
    public array $prompts, $default_channel_ids;
    public bool $enabled;
    public int $mode;

    private function onboarding_mode_converter(string $name){
        $array = [
            "ONBOARDING_DEFAULT" => 0,
            "ONBOARDING_ADVANCED" => 1
        ];

        if(isset($array[$name])){
            $value = $array[$name];
            return $value;
        } else {
            return Null;
        }
    }

    public function get_guild_onboarding(string $guild_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/onboarding";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["guild_id", "prompts", "default_channel_ids", "enabled", "mode"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
        
    }

    public function modify_guild_onboarding(string $guild_id, ?string $audit_log_reason,
    array $prompts, array $default_channel_ids, bool $enabled, string|int $mode){

        if(gettype($mode) == "string"){
            $mode = $this->onboarding_mode_converter($mode);
        }

        $data = [
            "prompts" => $prompts,
            "default_channel_ids" => $default_channel_ids,
            "enabled" => $enabled,
            "mode" => $mode
        ];

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/onboarding";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->put($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["guild_id", "prompts", "default_channel_ids", "enabled", "mode"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    

}

?>