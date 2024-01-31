<?php

namespace Discord\User\ApplicationRoleConnection;

use React;
use Psr;
require "../../vendor/autoload.php";

class Metadata{
    public string $type, $key, $name, $description;
    public array $name_localizations, $description_localizations;

    private array $records_container;

    public function get_from_object(object $object){
        $metadata = $object->metadata;
        
        $dataList = ["type", "key", "name", "name_localizations", "description", "description_localizations"];
        foreach($dataList as $key){
            if(isset($metadata->$key)){
                $this->$key = $metadata->$key;
            } else {
                $this->$key = Null;
            }
        }
    }

    public function get_records(string $application_id, ?int $index){
        $conf = new \Discord\ConfigLoad;
        $conf->load();
        $token = $conf->token;

        $url = "https://discord.com/api/v10/users/@me/applications/" . $application_id . "/role-connections/metadata";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];
        
        $browser = new React\Http\Browser();
        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response) use($index){
            $metadatas = json_decode($response->getBody());
            
            if(isset($index)){
                $metadata = $metadatas[$index];

                $keyList = ["type", "key", "name", "name_localizations", "description", "description_localizations"];
                foreach($keyList as $key){
                    if(isset($metadata->$key)){
                        $this->$key = $metadata->$key;
                    } else {
                        $this->$key = Null;
                    }
                }
                return $metadata;
            } else {
                return $metadatas;
            }

        });
    }

    public function add_records(int|string $type, string $key, string $name, string $description,
    ?array $name_localizations, ?array $description_localizations){

        if(!isset($this->records_container)){
            $this->records_container=[];
        }

        $data = [
            "type" => $type,
            "key" => $key,
            "name" => $name,
            "description" => $description
        ];

        $pushData = [
            "name_localizations" => $name_localizations,
            "description_localizations" => $description_localizations
        ];
        $pushList = ["name_localizations", "description_localizations"];
        foreach($pushList as $push){
            if(isset($pushData[$push])){
                $pushArray = [
                    $push => $pushData[$push]
                ];
                $data = array_merge($data, $pushArray);
            }
        }

        array_push($this->records_container, $data);

    }

    public function set_records(string $application_id){
        $conf = new \Discord\ConfigLoad;
        $conf->load();
        $token = $conf->token;
        
        $url = "https://discord.com/api/v10/users/@me/applications/" . $application_id . "/role-connections/metadata";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];
        $data = $this->records_container;
        $json = json_encode($data);

        $browser = new React\Http\Browser();
        $browser->put($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $metadatas = json_decode($response->getBody());
            
            return $metadatas;

        });
    }
}

?>