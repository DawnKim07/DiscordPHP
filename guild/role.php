<?php

namespace Discord\Guild;

require "../vendor/autoload.php";
use Psr;
use React;

class Role{

    public string $id, $name, $icon, $unicode_emoji, $permissions;
    public int $colorm, $position, $flags;
    public bool $hoist, $managed, $mentionable;
    public object $tags;

    private array $role_positions;

    private function mfa_level_converter(string $level){
        $array = [
            "NONE" => 0,
            "ELEVATED" => 1
        ];

        if(isset($array[$level])){
            $value = $array[$level];
            return $value;
        } else {
            return Null;
        };
    }

    public function get_guild_roles(string $guild_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/roles";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            return $data;
        });
    }

    public function create_guild_role(string $guild_id, ?string $audit_log_reason,
    string $name, string $permissions, int $color, bool $hoist,
    ?\Discord\DataFormat\Image $icon, ?string $unicode_emoji, bool $mentionable){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [
            "name" => $name,
            "permissions" => $permissions,
            "color" => $color,
            "hoist" => $hoist,
            "mentionable" => $mentionable
        ];

        $dataList = [
            "icon" => $icon,
            "unicode_emoji" => $unicode_emoji
        ];
        $dataKeyList = ["icon", "unicode_emoji"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/roles";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "name", "color", "hoist", "icon", "unicode_emoji", "position",
            "permissions", "managed", "mentionable", "tags", "flags"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function add_role_positions(string $id, ?int $position){
        if(!isset($this->role_positions)){
            $this->role_positions = [];
        }
        
        $add_data = [
            "id" => $id,
            "position" => $position
        ];

        $this->role_positions = array_push($this->role_positions, $add_data);
    }

    public function set_role_positions(string $guild_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = $this->role_positions;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/roles";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            return $data;
        });
    }

    public function modify_guild_role(string $guild_id, string $role_id, ?string $audit_log_reason,
    ?string $name, ?string $permissions, ?int $color, ?bool $hoist, ?\Discord\DataFormat\Image $icon,
    ?string $unicode_emoji, ?bool $mentionable){

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [];

        $dataList = [
            "name" => $name,
            "permissions" => $permissions,
            "color" => $color,
            "hoist" => $hoist,
            "icon" => $icon,
            "unicode_emoji" => $unicode_emoji,
            "mentionable" => $mentionable
        ];
        $dataKeyList = ["name", "permissions", "color", "hoist", "icon", "unicode_emoji",
        "mentionable"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/roles/" . $role_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "name", "color", "hoist", "icon", "unicode_emoji", "position",
            "permissions", "managed", "mentionable", "tags", "flags"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function modify_guild_mfa_level(string $guild_id, int|string $level,
    ?string $audit_log_reason){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        if(gettype($level)=="string"){
            $level = $this->mfa_level_converter($level);
        }

        $data = [
            "level" => $level
        ];

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/mfa";
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

    public function delete_guild_role(string $guild_id, string $role_id, ?string $audit_log_reason){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/roles/" . $role_id;
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