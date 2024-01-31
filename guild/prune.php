<?php

namespace Discord\Guild;

use Psr;
use React;
require "../vendor/autoload.php";

class Prune{

    public function get_guild_prune_count(string $guild_id, int $days=7, string|array $include_roles=Null){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;
        
        $query = "?days=" . "$days";
        
        if(isset($include_roles)){
            $processed_array = "";
            for($i=0; $i<count($include_roles); $i++){
                $processed_array = $processed_array . $include_roles[$i] . ",";
                if($i==(count($include_roles)-1)){
                    $processed_array = $processed_array . $include_roles[$i];
                }
            }
            $query = $query . "&include_roles=" . $processed_array;
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/prune" . $query;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            return $data;
        });

    }

    public function begin_guild_prune(string $guild_id, ?string $audit_log_reason,
    int $days, bool $compute_prune_count, array $include_roles){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [
            "days" => $days,
            "compute_prune_count" => $compute_prune_count,
            "include_roles" => $include_roles
        ];

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            return $data;
        });

    }

}

?>