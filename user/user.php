<?php

namespace Discord;

use React;
use Psr;
require "../vendor/autoload.php";

class User{
    public string $id, $username, $discriminator, $global_name, $avatar, $banner, $locale, $email, $avatar_decoration;
    public bool $bot, $system, $mfa_enabled, $verified;
    public int $accent_color, $flags, $premium_type, $public_flags;

    public function get_current_user(){
        $conf = new ConfigLoad;
        $conf->load();
        $token = $conf->token;

        $url = "https://discord.com/api/v10/users/@me";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];
        
        $browser = new React\Http\Browser();
        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $user = json_decode($response->getBody());
            
            $keyList = ["id", "username", "discriminator", "global_name", "avatar", "bot", "system",
            "mfa_enabled", "banner", "accent_color", "locale", "verified", "email", "flags", "premium_type",
            "public_flags", "avatar_decoration"];
            foreach($keyList as $key){
                if(isset($user->$key)){
                    $this->$key = $user->$key;
                } else {
                    $this->$key = Null;
                }
            }
        });
    }

    public function get(string $user_id){
        $conf = new ConfigLoad;
        $conf->load();
        $token = $conf->token;

        $url = "https://discord.com/api/v10/users/" . $user_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];
        
        $browser = new React\Http\Browser();
        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $user = json_decode($response->getBody());
            
            $keyList = ["id", "username", "discriminator", "global_name", "avatar", "bot", "system",
            "mfa_enabled", "banner", "accent_color", "locale", "verified", "email", "flags", "premium_type",
            "public_flags", "avatar_decoration"];
            foreach($keyList as $key){
                if(isset($user->$key)){
                    $this->$key = $user->$key;
                } else {
                    $this->$key = Null;
                }
            }
        });
    }

    public function edit_current_user(?string $username, ?\Discord\DataFormat\Image $avatar){
        $conf = new ConfigLoad;
        $conf->load();
        $token = $conf->token;

        $data = [];
        $addData = [
            "username" => $username,
            "avatar" => $avatar->set_image()
        ];
        $addList = ["username", "avatar"];

        foreach($addList as $addKey){
            if(isset($addData[$addKey])){
                $newData = [
                    $addKey => $addData[$addKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $url = "https://discord.com/api/v10/users/@me";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser = new React\Http\Browser();
        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $user = json_decode($response->getBody());
            
            $keyList = ["id", "username", "discriminator", "global_name", "avatar", "bot", "system",
            "mfa_enabled", "banner", "accent_color", "locale", "verified", "email", "flags", "premium_type",
            "public_flags", "avatar_decoration"];
            foreach($keyList as $key){
                if(isset($user->$key)){
                    $this->$key = $user->$key;
                } else {
                    $this->$key = Null;
                }
            }
        });
    }

}

?>