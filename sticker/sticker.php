<?php

namespace Discord;

use Psr;
use React;

require "../vendor/autoload.php";

class Sticker{

    public string $id, $pack_id, $name, $description, $tags, $asset, $guild_id;
    public int $type, $format_type, $sort_value;
    public bool $available;
    public object $user;

    private function sticker_types_converter(string $type){
        $array = [
            "STANDARD" => 1,
            "GUILD" => 2
        ];

        if(isset($array[$type])){
            $return = $array[$type];
            return $return;
        } else {
            return Null;
        }
    }

    private function format_types_converter(string $type){
        $array = [
            "PNG" => 1,
            "APNG" => 2,
            "LOTTIE" => 3,
            "GIF" => 4
        ];

        if(isset($array[$type])){
            $return = $array[$type];
            return $return;
        } else {
            return Null;
        }
    }

    public function get_sticker(string $sticker_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/stickers/" . $sticker_id;
        $header = [
            "Authorization" => "Bot" . " " . $token 
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "pack_id", "name", "description", "tags", "asset", "type",
            "format_type", "available", "guild_id", "user", "sort_value"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function get_guild_stickers(string $guild_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/stickers";
        $header = [
            "Authorization" => "Bot" . " " . $token 
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            return $data;
        });
    }

    public function get_guild_sticker(string $guild_id, string $sticker_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/stickers/" . $sticker_id;
        $header = [
            "Authorization" => "Bot" . " " . $token 
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "pack_id", "name", "description", "tags", "asset", "type",
            "format_type", "available", "guild_id", "user", "sort_value"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function create_guild_sticker(string $guild_id, string $audit_log_reason,
    string $name, string $description, string $tags, string $file_location){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $JsonData = [
            "name" => $name,
            "description" => $description,
            "tags" => $tags
        ];
        $ImageData = file_get_contents($file_location);

        $FileName = basename($file_location);
        $FileFormat = explode(".", $FileName)[1];

        $FormatArray = ["png", "apng", "gif", "json"];
        if(in_array($FileFormat, $FormatArray)){
            if($FileFormat == "json"){
                $FormatType = "application";
            } else {
                $FormatType = "image";
            }
        }

        $UID = com_create_guid();
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "multipart/form-data; boundary=--" . $UID
        ];

        $body = 
        "--$UID
        Content-Disposition: form-data; name=" .'"payload_json"
        '. "Content-Type: application/json
        
        {
            " . '"name": ' . '"' . $name . '",
        ' . '   "description": ' . '"' . $description . '",
        ' . '   "tags": ' . '"' . $tags . '"
        }
        ' . "--$UID
        " . 'Content-Disposition: form-data; name="files[0]"; filename="' . $FileName . '"
        Content-Type: ' . $FormatType . "/" . $FileFormat . "
        
        $ImageData
        --$UID--";

        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/stickers";

        $browser = new React\Http\Browser();
        $browser->post($url, $header, $body)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "pack_id", "name", "description", "tags", "asset", "type",
            "format_type", "available", "guild_id", "user", "sort_value"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function modify_guild_sticker(string $guild_id, string $sticker_id,
    ?string $audit_log_reason, ?string $name, ?string $description, ?string $tags){
        $data = [];

        $dataList = [
            "name" => $name,
            "description" => $description,
            "tags" => $tags
        ];
        $dataKeyList = ["name", "description", "tags"];

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
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/stickers/" . $sticker_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "pack_id", "name", "description", "tags", "asset", "type",
            "format_type", "available", "guild_id", "user", "sort_value"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function delete_guild_sticker(string $guild_id, string $sticker_id,
    ?string $audit_log_reason){

        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/stickers/" . $sticker_id;
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