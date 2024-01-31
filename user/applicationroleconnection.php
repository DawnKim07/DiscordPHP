<?php

namespace Discord\User;

use React;
use Psr;
require "../vendor/autoload.php";

class ApplicationRoleConnection{

    public string $platform_name, $platform_username;
    public ApplicationRoleConnection\Metadata $metadata;

    public function get_current_user_application_role_connections(string $application_id){
        $conf = new \Discord\ConfigLoad;
        $conf->load();
        $token = $conf->token;

        $url = "https://discord.com/api/v10/users/@me/applications/" . $application_id . "/role-connection";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];
        
        $browser = new React\Http\Browser();
        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $application_role_connection = json_decode($response->getBody());
            
            $keyList = ["platform_name", "platform_username", "metadata"];
            foreach($keyList as $key){
                if(isset($application_role_connection->$key)){
                    $this->$key = $application_role_connection->$key;
                } else {
                    $this->$key = Null;
                }
            }
            return $application_role_connection;

        });
    }
}

?>