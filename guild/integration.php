<?php

namespace Discord\Guild;

use Psr;
use React;
require "../vendor/autoload.php";

class Integration{
    public string $id, $name, $type, $role_id;
    public bool $enabled, $syncing, $enable_emoticons, $revoked;
    public int $expire_grace_period, $subscriber_count, $expire_behavior;
    public object $user, $account, $application;
    public array $scopes;

    public function get_guild_integrations(string $guild_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/integrations";
        $header = [
            "Authorization" => "Bot" . " " . $token 
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            return $data;
        });
    }

    public function delete_guild_integration(string $guild_id, string $integration_id,
    ?string $audit_log_reason){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/integrations/" . $integration_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason 
        ];

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

}

?>