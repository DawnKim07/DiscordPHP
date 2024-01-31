<?php

namespace Discord\Guild;

use Psr;
use React;

require "../vendor/autoload.php";

class Widget{
    public string $id, $name, $instant_invite;
    public array $channels, $members;
    public int $presence_count;

    public function get_guild_widget(string $guild_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/widget.json";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "name", "instant_invite", "channels", "members", "presence_count"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function get_guild_widget_image(string $guild_id, ?string $style="shield"){
        
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $query = "?query=" . $style;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/widget.png" . $query;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            return $data;
        });
    }
    

}

?>