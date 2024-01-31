<?php

namespace Discord;

use Psr;
use React;
require "../vendor/autoload.php";

class AuditLog{

    public array $application_commands, $audit_log_entries, $auto_moderation_rules, $guild_shceduled_events,
    $integrations, $threads, $users, $webhooks;

    public function get_guild_audit_log(string $guild_id, ?string $user_id, ?int $action_type,
    ?string $before, ?string $after, ?int $limit){

        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["application_commands", "audit_log_entries", "auto_moderation_rules", "guild_scheduled_events",
            "integrations", "threads", "users", "webhooks"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

}

?>