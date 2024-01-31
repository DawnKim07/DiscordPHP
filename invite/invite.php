<?php

namespace Discord;

use Psr;
use React;

require "../vendor/autoload.php";

class Invite{

    public string $code, $expires_at;
    public int $target_type, $approxiamte_presence_count, $approximate_member_count;
    public object $guild, $channel, $inviter, $target_user, $target_application,
    $stage_instance, $guild_shceduled_event;

    public function get(object $object, ?int $index){
        $invites = $object->invites;

        if($index == Null){
            $invite = $invites;
        } else {
            $invite = $invites[$index];
        }
        
        $keyList = ["code", "guild", "channel", "inviter", "target_type", "target_user", "target_application",
        "approximate_presence_count", "approximate_member_count", "expires_at", "stage_instance", "guild_scheduled_event"];
        foreach($keyList as $key){
            if(isset($invite->$key)){
                $this->$key = $invite->$key;
            } else {
                $this->$key = Null;
            }
        }

    }

    public function get_guild_invites(string $guild_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/invites";
        $header = [
            "Authorization" => "Bot" . " " . $token 
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            return $data;
        });
    }

    public function get_guild_vanity_url(string $guild_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/vanity-url";
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