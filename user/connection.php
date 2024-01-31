<?php

namespace Discord\User;

use React;
use Psr;
require "../vendor/autoload.php";

class Connection{
    public string $id, $name, $type;
    public bool $verified, $friend_sync, $show_activity, $two_way_link;
    public array $integrations;
    public int $visibility;

    public function get_current_user_connections(?int $index){
        $conf = new \Discord\ConfigLoad;
        $conf->load();
        $token = $conf->token;

        $url = "https://discord.com/api/v10/users/@me/connections";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];
        
        $browser = new React\Http\Browser();
        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response) use($index){
            $connections = json_decode($response->getBody());
            
            if(isset($index)){
                $connection = $connections[$index];

                $keyList = ["id", "name", "type", "verified", "friend_sync", "show_activity",
                "two_way_link", "intergrations", "visibility"];
                foreach($keyList as $key){
                    if(isset($connection->$key)){
                        $this->$key = $connection->$key;
                    } else {
                        $this->$key = Null;
                    }
                }
                return $connection;
                
            } else {
                return $connections;
            }

        });
    }

}

?>