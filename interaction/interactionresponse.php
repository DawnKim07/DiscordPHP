<?php

namespace Discord\Interaction;

use Psr;
use React;
require "../vendor/autoload.php";

class Response{
    public int $type;
    public object $data;

    public function create_interaction_response(string $interaction_id, string $interaction_token,
    int|string $type, ?\Discord\Interaction\Response\Messages $messages,
    ?\Discord\Interaction\Response\AutoComplete $autocomplete, 
    ?\Discord\Interaction\Response\Modal $modal){

        /*
        Be sure not to give values to more than 1 variable,
        among '$messages', '$autocomplete', and '$modal'
        : since only the last value-set variable would be written.
        */

        $data = [
            "type" => $type
        ];

        if(isset($messages)|isset($autocomplete)|isset($modal)){
            $array = [$messages, $autocomplete, $modal];
            foreach($array as $data){
                if(isset($data)){
                    $addData = [
                        "data" => $data
                    ];
                }
            }
            $data = array_merge($data, $addData);
        }

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/interactions/" . $interaction_id . "/"
        . $interaction_token . "/callback";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });

    }

    public function get_original_interaction_response(string $application_id,
    string $interaction_token, ?string $thread_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/"
        . $interaction_token . "/messages/@original";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        if(isset($thread_id)){
            $query = "?thread_id=" . $thread_id;
            $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/"
            . $interaction_token . "/messages/@original" . $query;
        }

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["type", "data"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }
    
    public function edit_original_interaction_response(string $application_id,
    string $interaction_token, ?string $thread_id, ?string $content, ?array $embeds,
    ?\Discord\Channel\Message\AllowedMentions $allowed_mentions, ?array $components){

        $data = [];
        $dataList = [
            "content" => $content,
            "embeds" => $embeds,
            "allowed_mentions" => $allowed_mentions,
            "components" => $components
        ];
        $dataKeyList = ["content", "embeds", "allowed_mentions", "components"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/"
        . $interaction_token . "/messages/@original";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];

        if(isset($thread_id)){
            $query = "?thread_id=" . $thread_id;
            $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/"
            . $interaction_token . "/messages/@original" . $query;
        }

        $json = json_encode($data);
        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["type", "data"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function edit_original_interaction_response_with_files(string $application_id,
    string $interaction_token, ?string $thread_id, ?string $content, ?array $embeds,
    ?\Discord\Channel\Message\AllowedMentions $allowed_mentions, ?array $components,
    ?array $files, ?array $attachments){

        $data = [];
        $dataList = [
            "content" => $content,
            "embeds" => $embeds,
            "allowed_mentions" => $allowed_mentions,
            "components" => $components,
            "attachments" => $attachments
        ];
        $dataKeyList = ["content", "embeds", "allowed_mentions", "components", "attachments"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $UID = com_create_guid();

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/"
        . $interaction_token . "/messages/@original";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "multipart/form-data; boundary=--$UID"
        ];

        if(isset($thread_id)){
            $query = "?thread_id=" . $thread_id;
            $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/"
            . $interaction_token . "/messages/@original" . $query;
        }

        $ExtFunc = new \ExtFunction;
        $body = $ExtFunc->build_multipart_body($UID, $data, $files);
        
        $browser->patch($url, $header, $body)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["type", "data"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function delete_original_interaction_response(string $application_id,
    string $interaction_token, ?string $thread_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/"
        . $interaction_token . "/messages/@original";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        if(isset($thread_id)){
            $query = "?thread_id=" . $thread_id;
            $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/"
            . $interaction_token . "/messages/@original" . $query;
        }

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

}

?>