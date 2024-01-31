<?php

namespace Discord\Guild;

use Psr;
use React;

require "../vendor/autoload.php";

class WelcomeScreen{
    public string $description;
    public array $welcome_channels;

    public function get_guild_welcome_screen(string $guild_id, ?string $audit_log_reason,
    ?bool $enabled, ?array $welcome_channels, ?string $description){
        $data = [];

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $dataList = [
            "enabled" => $enabled,
            "welcome_channels" => $welcome_channels,
            "description" => $description
        ];
        $dataKeyList = ["enabled", "welcome_channels", "description"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/welcome-screen";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["description", "welcome_channels"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

}

?>