<?php

namespace Discord\Application;

use Psr;
use React;

class ApplicationRoleConnectionMetadata{

    public int $type;
    public string $key, $name, $description;
    public array $name_localizations, $description_localizations;

    public function get_application_role_connection_metadata_records(string $application_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/applications/" . $application_id . "/role-connections/metadata";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            return $data;
        });

    }

    public function update_application_role_connection_metadata_records(string $application_id,
    int $type, string $key, string $name, ?array $name_localizations, string $description,
    ?array $description_localizations){

        $data = [
            "application_id" => $application_id,
            "type" => $type,
            "key" => $key,
            "name" => $name
        ];

        $OptionalData = [
            "name_localizations" => $name_localizations,
            "description_localizations" => $description_localizations
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

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/applications/" . $application_id . "/role-connections/metadata";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->put($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            return $data;
        });

    }

}

?>