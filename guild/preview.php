<?php

namespace Discord\Guild;

use Psr;
use React;
require "../vendor/autoload.php";

class Preview{
    public string $id, $name, $icon, $splash, $discovery_splash, $description;
    public array $emojis, $features, $stickers;
    public int $approximate_member_count, $approximate_presence_count;

    public function get_guild_preview(string $guild_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/preview";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "name", "icon", "splash", "discovery_splash", "description",
            "emojis", "features", "stickers", "approximate_member_count", 
            "approximate_presence_count"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

}

?>