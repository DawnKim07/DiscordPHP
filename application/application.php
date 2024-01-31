<?php

namespace Discord;

use Psr;
use React;
require "../vendor/autoload.php";

class Application{

    public string $id, $name, $icon, $description, $terms_of_service_url, $privacy_policy_url,
    $verify_key, $guild_id, $primary_sku_id, $slug, $cover_image, $interactions_endpoint_url,
    $role_connections_verification_url, $custom_install_url;
    public array $rpc_origins, $redirect_urls, $tags;
    public bool $bot_public, $bot_require_code_grant;
    public object $bot, $owner, $team, $guild, $install_params;
    public int $flags, $approximate_guild_count;

    public function get_current_application(){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/applications/@me";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "name", "icon", "description", "rpc_origins", "bot_public",
            "bot_require_code_grant", "bot", "terms_of_service_url", "privacy_policy_url",
            "owner", "verify_key", "team", "guild_id", "guild", "primary_sku_id", "slug",
            "cover_image", "flags", "approximate_guild_count", "redirect_urls",
            "interactions_endpoint_url", "tags", "install_params", "custom_install_url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function edit_current_application(?string $custom_install_url, ?string $description,
    ?string $role_connections_verification_url, ?\Discord\Application\InstallParams $install_params,
    ?int $flags, ?\Discord\DataFormat\Image $icon, ?\Discord\DataFormat\Image $cover_image,
    ?string $interactions_endpoint_url, ?array $tags){
    
        $data = [];

        $OptionalData = [
            "custom_install_url" => $custom_install_url,
            "description" => $description,
            "role_connections_verification_url" => $role_connections_verification_url,
            "install_params" => $install_params,
            "flags" => $flags,
            "icon" => $icon,
            "cover_image" => $cover_image,
            "interactions_endpoint_url" => $interactions_endpoint_url,
            "tags" => $tags
        ];

        while($OptionalValue = current($OptionalData)){
            if(isset($OptionalValue)){
                $OptionalKey = key($OptionalValue);
                $AddData = [
                     $OptionalKey => $OptionalValue
                ];
                $data = array_merge($data, $AddData);
            }
        }

        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/applications/@me";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "name", "icon", "description", "rpc_origins", "bot_public",
            "bot_require_code_grant", "bot", "terms_of_service_url", "privacy_policy_url",
            "owner", "verify_key", "team", "guild_id", "guild", "primary_sku_id", "slug",
            "cover_image", "flags", "approximate_guild_count", "redirect_urls",
            "interactions_endpoint_url", "tags", "install_params", "custom_install_url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

}

?>