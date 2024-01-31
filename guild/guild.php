<?php

namespace Discord;

use Psr;
use React;
require "../vendor/autoload.php";

class Guild{
    public string $id, $name, $icon, $icon_hash, $splash, $discovery_splash, $owner_id, 
    $permissions, $region, $afk_channel_id, $widget_channel_id, $application_id, 
    $system_channel_id, $vanity_url_code, $description, $banner,
    $preferred_locale, $rules_channel_id, $public_updates_channel_id, $safety_alerts_channel_id;
    public bool $owner, $widget_enabled, $premium_progress_bar_enabled;
    public int $afk_timeout, $verification_level, $default_message_notification, 
    $explicit_content_filter, $mfa_level, $system_channel_flags, $max_presences, $max_members,
    $premium_tier, $premium_subscription_count, $max_video_channel_users,
    $max_stage_video_channel_users, $approximate_member_count, $approximate_presence_count,
    $nsfw_level;
    public array $roles, $emojis, $features, $stickers;

    private function default_message_notification_level_converter(string $key){
        $array = [
            "ALL_MESSAGES" => 0,
            "ONLY_MENTIONS" => 1
        ];

        if(isset($array[$key])){
            $value = $array[$key];
            return $value;
        } else {
            return Null;
        };
    }

    private function explicit_content_filter_level_converter(string $level){
        $array = [
            "DISABLED" => 0,
            "MEMBERS_WITHOUT_ROLES" => 1,
            "ALL_MEMBERS" => 2
        ];

        if(isset($array[$level])){
            $value = $array[$level];
            return $value;
        } else {
            return Null;
        };
    }

    private function verification_level_converter(string $level){
        $array = [
            "NONE" => 0,
            "LOW" => 1,
            "MEDIUM" => 2,
            "HIGH" => 3,
            "VERY_HIGH" => 4
        ];

        if(isset($array[$level])){
            $value = $array[$level];
            return $value;
        } else {
            return Null;
        }; 
    }

    private function guild_nsfw_level_converter(string $level){
        $array = [
            "DEFAULT" => 0,
            "EXPLICIT" => 1,
            "SAFE" => 2,
            "AGE_RESTRICTED" => 2
        ];

        if(isset($array[$level])){
            $value = $array[$level];
            return $value;
        } else {
            return Null;
        }; 
    }

    private function premium_tier_converter(string $level){
        $array = [
            "NONE" => 0,
            "TIER_1" => 1,
            "TIER_2" => 2,
            "TIER_3" => 3
        ];

        if(isset($array[$level])){
            $value = $array[$level];
            return $value;
        } else {
            return Null;
        }; 
    }

    public function create_guild(string $name, ?string $region, ?\Discord\DataFormat\Image $icon, 
    int|string $verification_level=Null, int|string $default_message_notifications=Null, 
    int|string $explicit_content_filter=Null, ?array $roles, ?array $channels, 
    ?string $afk_channel_id, ?int $afk_timeout, ?string $system_channel_id, 
    ?int $system_channel_flags){

        if(gettype($verification_level)=="string"){
            $verification_level = $this->verification_level_converter($verification_level);
        }

        if(gettype($default_message_notifications)=="string"){
            $default_message_notifications = $this->default_message_notification_level_converter($default_message_notifications);
        }

        if(gettype($explicit_content_filter)=="string"){
            $explicit_content_filter = $this->explicit_content_filter_level_converter($explicit_content_filter);
        }

        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [
            "name" => $name,
            "region" => $region
        ];

        $dataList = [
            "icon" => $icon,
            "verification_level" => $verification_level,
            "default_message_notifications" => $default_message_notifications,
            "explicit_content_filter" => $explicit_content_filter,
            "roles" => $roles,
            "channels" => $channels,
            "afk_channel_id" => $afk_channel_id,
            "afk_timeout" => $afk_timeout,
            "system_channel_id" => $system_channel_id,
            "system_channel_flags" => $system_channel_flags
        ];
        $dataKeyList = ["icon", "verification_level", "default_message_notification",
        "explicit_content_filter", "roles", "channels", "afk_channel_id", "afk_timeout",
        "system_channel_id", "system_channel_flags"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "name", "icon", "icon_hash", "splash", "discovery_splash", "owner_id", 
            "permissions", "region", "afk_channel_id", "widget_channel_id", "application_id", 
            "system_channel_id", "vanity_url_code", "description", "banner",
            "preferred_locale", "rules_channel_id", "public_updates_channel_id", "safety_alerts_channel_id",
            "owner", "widget_enabled", "premium_progress_bar_enabled",
            "afk_timeout", "verification_level", "default_message_notification", 
            "explicit_content_filter", "mfa_level", "system_channel_flags", "max_presences", "max_members",
            "premium_tier", "premium_subscription_count", "max_video_channel_users",
            "max_stage_video_channel_users", "approximate_member_count", "approximate_presence_count",
            "nsfw_level"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function get_guild(?bool $with_counts, string $guild_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        if(gettype($with_counts)!=Null){
            $query = "?with_counts=" . "$with_counts";
            $url = "https://discord.com/api/v10/guilds/" . $guild_id . $query;
        }

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "name", "icon", "icon_hash", "splash", "discovery_splash", "owner_id", 
            "permissions", "region", "afk_channel_id", "widget_channel_id", "application_id", 
            "system_channel_id", "vanity_url_code", "description", "banner",
            "preferred_locale", "rules_channel_id", "public_updates_channel_id", "safety_alerts_channel_id",
            "owner", "widget_enabled", "premium_progress_bar_enabled",
            "afk_timeout", "verification_level", "default_message_notification", 
            "explicit_content_filter", "mfa_level", "system_channel_flags", "max_presences", "max_members",
            "premium_tier", "premium_subscription_count", "max_video_channel_users",
            "max_stage_video_channel_users", "approximate_member_count", "approximate_presence_count",
            "nsfw_level"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function modify_guild(string $guild_id, ?string $audit_log_reason, ?string $name,
    ?string $region, int|string $verification_level=Null, 
    int|string $default_message_notifications=Null, int|string $explicit_content_filter=Null, 
    ?string $afk_channel_id, ?int $afk_timeout, ?\Discord\DataFormat\Image $icon, 
    ?string $owner_id, ?\Discord\DataFormat\Image $splash,
    ?\Discord\DataFormat\Image $discovery_splash, ?\Discord\DataFormat\Image $banner,
    ?string $system_channel_id, ?int $system_channel_flags, ?string $rules_channel_id,
    ?string $public_updates_channel_id, ?string $preferred_locale, ?array $features,
    ?string $description, ?bool $premium_progress_bar_enabled, ?string $safety_alerts_channel_id){

        if(gettype($verification_level)=="string"){
            $verification_level = $this->verification_level_converter($verification_level);
        }

        if(gettype($default_message_notifications)=="string"){
            $default_message_notifications = $this->default_message_notification_level_converter($default_message_notifications);
        }

        if(gettype($explicit_content_filter)=="string"){
            $explicit_content_filter = $this->explicit_content_filter_level_converter($explicit_content_filter);
        }

        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [];

        $dataList = [
            "name" => $name,
            "region" => $region,
            "verification_level" => $verification_level,
            "default_message_notifications" => $default_message_notifications,
            "explicit_content_filter" => $explicit_content_filter,
            "afk_channel_id" => $afk_channel_id,
            "afk_timeout" => $afk_timeout,
            "icon" => $icon,
            "owner_id" => $owner_id,
            "splash" => $splash,
            "discovery_splash" => $discovery_splash,
            "banner" => $banner,
            "system_channel_id" => $system_channel_id,
            "system_channel_flags" => $system_channel_flags,
            "rules_channel_id" => $rules_channel_id,
            "public_updates_channel_id" => $public_updates_channel_id,
            "preferred_locale" => $preferred_locale,
            "features" => $features,
            "description" => $description,
            "premium_progress_bar_enabled" => $premium_progress_bar_enabled,
            "safety_alerts_channel_id" => $safety_alerts_channel_id
        ];
        $dataKeyList = ["name", "region", "verification_level", "default_message_notifications",
        "explicit_content_filter", "afk_channel_id", "afk_timeout", "icon", "owner_id",
        "splash", "discovery_splash", "banner", "system_channel_id", "system_channel_flags",
        "rules_channel_id", "public_updates_channel_id", "preferred_locale",
        "features", "description", "premium_progress_bar_enabled", "safety_alerts_channel_id"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "name", "icon", "icon_hash", "splash", "discovery_splash", "owner_id", 
            "permissions", "region", "afk_channel_id", "widget_channel_id", "application_id", 
            "system_channel_id", "vanity_url_code", "description", "banner",
            "preferred_locale", "rules_channel_id", "public_updates_channel_id", "safety_alerts_channel_id",
            "owner", "widget_enabled", "premium_progress_bar_enabled",
            "afk_timeout", "verification_level", "default_message_notification", 
            "explicit_content_filter", "mfa_level", "system_channel_flags", "max_presences", "max_members",
            "premium_tier", "premium_subscription_count", "max_video_channel_users",
            "max_stage_video_channel_users", "approximate_member_count", "approximate_presence_count",
            "nsfw_level"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function delete_guild(string $guild_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function modify_guild_channel_positions(string $guild_id, string $id,
    ?int $position, ?bool $lock_permissions, ?string $parent_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [
            "id" => $id
        ];

        $dataList = [
            "position" => $position,
            "lock_permissions" => $lock_permissions,
            "parent_id" => $parent_id
        ];

        $dataKeyList = ["position", "lock_permissions", "parent_id"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/channels";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function list_active_guild_threads(string $guild_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/threads/active";
        $header = [
            "Authorization" => "Bot" . " " . $token 
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $return = [
                "threads" => $data->threads,
                "members" => $data->members
            ];
            return $return;
        });
    }

    public function modify_current_user_voice_state(string $guild_id, ?string $channel_id,
    ?bool $suppress, ?string $request_to_speak_timestamp){
        $data = [];

        $dataList = [
            "channel_id" => $channel_id,
            "suppress" => $suppress,
            "request_to_speak_timestamp" => $request_to_speak_timestamp
        ];
        $dataKeyList = ["channel_id", "suppress", "request_to_speak_timestamp"];

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
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/voice-states/@me";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function modify_user_voice_state(string $guild_id, string $user_id, string $channel_id,
    ?bool $suppress){
        $data = [
            "channel_id" => $channel_id
        ];

        if(isset($suppress)){
            $addData = [
                "suppress" => $suppress
            ];
            $data = array_merge($data, $addData);
        }

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/voice-states/" . $user_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function create_guild_from_guild_template(string $template_code, string $name,
    ?\Discord\DataFormat\Image $icon){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [
            "name" => $name
        ];

        if(isset($icon)){
            $addData = [
                "icon" => $icon
            ];
            $data = array_merge($data, $addData);
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/templates/" . $template_code;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "name", "icon", "icon_hash", "splash", "discovery_splash", "owner_id", 
            "permissions", "region", "afk_channel_id", "widget_channel_id", "application_id", 
            "system_channel_id", "vanity_url_code", "description", "banner",
            "preferred_locale", "rules_channel_id", "public_updates_channel_id", "safety_alerts_channel_id",
            "owner", "widget_enabled", "premium_progress_bar_enabled",
            "afk_timeout", "verification_level", "default_message_notification", 
            "explicit_content_filter", "mfa_level", "system_channel_flags", "max_presences", "max_members",
            "premium_tier", "premium_subscription_count", "max_video_channel_users",
            "max_stage_video_channel_users", "approximate_member_count", "approximate_presence_count",
            "nsfw_level"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

}

?>