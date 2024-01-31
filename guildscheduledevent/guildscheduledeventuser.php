<?php

namespace Discord\GuildScheduledEvent;

use Psr;
use React;
require "../vendor/autoload.php";

class User{
    public string $guild_scheduled_event_id;
    public object $user, $member;

    public function get_guild_scheduled_event_users(string $guild_id, string $guild_scheduled_event_id,
    ?int $limit=100, ?bool $with_member=False, ?string $before=Null, ?string $after=Null){

        $query = "?limit=" . "$limit" . "&with_member=" . "$with_member" . "&before=" . "$before"
        . "&after=" . "$after";

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/scheduled-events/"
        . $guild_scheduled_event_id . "/users" . $query;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["guild_scheduled_event_id", "user", "member"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

}

?>