<?php

namespace Discord;

use React;
use Psr;
require __DIR__ . '/../vendor/autoload.php';

class AppCommand{
    public string $id, $application_id, $guild_id, $name, $description, $default_member_permissions, $version;
    public array $name_localizations, $description_localizations;
    public bool $dm_permission, $default_permission, $nsfw;
    public $type;

    public object $options;
    
    private $command_container;

    private function typeConverter(string $type){
        $array = [
            "CHAT_INPUT" => 1,
            "USER" => 2,
            "MESSAGE" => 3
        ];

        if(isset($array[$type])){
            return $array[$type];
        }
    }

    public function get_all_global(?bool $with_localizations = False){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $application_id = $loader->app_id;

        $urlQuery = http_build_query([
            "with_localizations" => $with_localizations
        ]);

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/applications/" . $application_id . "/commands?" . $urlQuery;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList = ["id", "type", "application_id", "guild_id", "name", "name_localizations", "description",
            "description_localizations", "options", "default_member_permissions", "dm_permission", "nsfw", "version"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function create_global(string $name, ?array $name_localizations, ?string $description, ?array $description_localizations,
    ?AppCommand\Options $options, ?string $default_member_permissions, ?bool $dm_permission, int|string $type=1, ?bool $nsfw){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $application_id = $loader->app_id;

        if(gettype($type)=="string"){
            $type = $this->typeConverter($type);
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/applications/" . $application_id . "/commands";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];

        $data = [
            "name" => $name
        ];

        $dataList = [
            "name_localizations" => $name_localizations,
            "description" => $description,
            "description_localizations" => $description_localizations,
            "options" => $options->set_embeds(),
            "default_member_permissions" => $default_member_permissions,
            "dm_permission" => $dm_permission,
            "type" => $type,
            "nsfw" => $nsfw
        ];
        $dataKeyList = ["name_localizations", "description", "description_localizations", "options",
        "default_member_permissions", "dm_permission", "type", "nsfw"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList = ["id", "type", "application_id", "guild_id", "name", "name_localizations", "description",
            "description_localizations", "options", "default_member_permissions", "dm_permission", "nsfw", "version"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function get_global(string $command_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $application_id = $loader->app_id;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/applications/" . $application_id . "/commands/" . $command_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList = ["id", "type", "application_id", "guild_id", "name", "name_localizations", "description",
            "description_localizations", "options", "default_member_permissions", "dm_permission", "nsfw", "version"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function edit_global(string $command_id, ?string $name, ?array $name_localizations, ?string $description, ?array $description_localizations,
    ?AppCommand\Options $options, ?string $default_member_permissions, ?bool $dm_permission, ?bool $nsfw){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $application_id = $loader->app_id;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/applications/" . $application_id . "/commands/" . $command_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];

        $data = [
            "name" => $name
        ];

        $dataList = [
            "name_localizations" => $name_localizations,
            "description" => $description,
            "description_localizations" => $description_localizations,
            "options" => $options->set_embeds(),
            "default_member_permissions" => $default_member_permissions,
            "dm_permission" => $dm_permission,
            "nsfw" => $nsfw
        ];
        $dataKeyList = ["name_localizations", "description", "description_localizations", "options",
        "default_member_permissions", "dm_permission", "nsfw"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList = ["id", "type", "application_id", "guild_id", "name", "name_localizations", "description",
            "description_localizations", "options", "default_member_permissions", "dm_permission", "nsfw", "version"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function delete_global(string $command_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $application_id = $loader->app_id;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/applications/" . $application_id . "/commands/" . $command_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function add_command_global(string $name, ?array $name_localizations, ?string $description, ?array $description_localizations,
    ?AppCommand\Options $options, ?string $default_member_permissions, ?bool $dm_permission, int|string $type=1, ?bool $nsfw){
        if(!isset($this->command_container)){
            $this->command_container = [];
        }

        $command = [
            "name" => $name
        ];

        $dataList = [
            "name_localizations" => $name_localizations,
            "description" => $description,
            "description_localizations" => $description_localizations,
            "options" => $options->set_embeds(),
            "default_member_permissions" => $default_member_permissions,
            "dm_permission" => $dm_permission,
            "type" => $type,
            "nsfw" => $nsfw
        ];
        $dataKeyList = ["name_localizations", "description", "description_localizations", "options",
        "default_member_permissions", "dm_permission", "type", "nsfw"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $command = array_merge($command, $newData);
            }
        }

        array_push($this->command_container, $command);
    }

    public function overwrite_global(){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $application_id = $loader->app_id;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/applications/" . $application_id . "/commands";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $data = json_encode($this->command_container);

        $browser->put($url, $header, $data)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function get_all_guild(?bool $with_localizations = False){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $application_id = $loader->app_id;
        $guild_id = $loader->guild_id;

        $urlQuery = http_build_query([
            "with_localizations" => $with_localizations
        ]);

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/applications/" . $application_id . "/guilds/" . $guild_id . "/commands?" . $urlQuery;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList = ["id", "type", "application_id", "guild_id", "name", "name_localizations", "description",
            "description_localizations", "options", "default_member_permissions", "dm_permission", "nsfw", "version"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function create_guild(string $name, ?array $name_localizations, ?string $description, ?array $description_localizations,
    ?AppCommand\Options $options, ?string $default_member_permissions, ?bool $dm_permission, int|string $type=1, ?bool $nsfw){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $application_id = $loader->app_id;
        $guild_id = $loader->guild_id;

        if(gettype($type)=="string"){
            $type = $this->typeConverter($type);
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/applications/" . $application_id. "/guilds/" . $guild_id . "/commands";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        
        $data = [
            "name" => $name
        ];

        $dataList = [
            "name_localizations" => $name_localizations,
            "description" => $description,
            "description_localizations" => $description_localizations,
            "options" => $options->set_embeds(),
            "default_member_permissions" => $default_member_permissions,
            "dm_permission" => $dm_permission,
            "type" => $type,
            "nsfw" => $nsfw
        ];
        $dataKeyList = ["name_localizations", "description", "description_localizations", "options",
        "default_member_permissions", "dm_permission", "type", "nsfw"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList = ["id", "type", "application_id", "guild_id", "name", "name_localizations", "description",
            "description_localizations", "options", "default_member_permissions", "dm_permission", "nsfw", "version"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function get_guild(string $command_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $application_id = $loader->app_id;
        $guild_id = $loader->guild_id;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/applications/" . $application_id. "/guilds/" . $guild_id . "/commands/" . $command_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList = ["id", "type", "application_id", "guild_id", "name", "name_localizations", "description",
            "description_localizations", "options", "default_member_permissions", "dm_permission", "nsfw", "version"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function edit_guild(string $command_id, ?string $name, ?array $name_localizations, ?string $description, ?array $description_localizations,
    ?AppCommand\Options $options, ?string $default_member_permissions, ?bool $dm_permission, ?bool $nsfw){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $application_id = $loader->app_id;
        $guild_id = $loader->guild_id;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/applications/" . $application_id. "/guilds/" . $guild_id . "/commands/" . $command_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        
        $data = [
            "name" => $name
        ];

        $dataList = [
            "name_localizations" => $name_localizations,
            "description" => $description,
            "description_localizations" => $description_localizations,
            "options" => $options->set_embeds(),
            "default_member_permissions" => $default_member_permissions,
            "dm_permission" => $dm_permission,
            "nsfw" => $nsfw
        ];
        $dataKeyList = ["name_localizations", "description", "description_localizations", "options",
        "default_member_permissions", "dm_permission", "nsfw"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList = ["id", "type", "application_id", "guild_id", "name", "name_localizations", "description",
            "description_localizations", "options", "default_member_permissions", "dm_permission", "nsfw", "version"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function delete_guild(string $command_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $application_id = $loader->app_id;
        $guild_id = $loader->guild_id;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/applications/" . $application_id. "/guilds/" . $guild_id . "/commands/" . $command_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function add_command_guild(string $name, ?array $name_localizations, ?string $description, ?array $description_localizations,
    ?AppCommand\Options $options, ?string $default_member_permissions, ?bool $dm_permission, int|string $type=1, ?bool $nsfw){
        if(!isset($this->command_container)){
            $this->command_container = [];
        }
        
        $command = [
            "name" => $name
        ];

        $dataList = [
            "name_localizations" => $name_localizations,
            "description" => $description,
            "description_localizations" => $description_localizations,
            "options" => $options->set_embeds(),
            "default_member_permissions" => $default_member_permissions,
            "dm_permission" => $dm_permission,
            "type" => $type,
            "nsfw" => $nsfw
        ];
        $dataKeyList = ["name_localizations", "description", "description_localizations", "options",
        "default_member_permissions", "dm_permission", "type", "nsfw"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $command = array_merge($command, $newData);
            }
        }

        array_push($this->command_container, $command);
    }

    public function overwrite_guild(){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $application_id = $loader->app_id;
        $guild_id = $loader->guild_id;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/applications/" . $application_id. "/guilds/" . $guild_id . "/commands";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $data = json_encode($this->command_container);

        $browser->put($url, $header, $data)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

}

?>