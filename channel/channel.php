<?php

namespace Discord;

use Discord\Channel\Message\Attachments;
use React;
use Psr;
require __DIR__ . '/../vendor/autoload.php';

class Channel{
    public string $id, $guild_id, $name, $topic, $last_message_id, $icon, $owner_id, $application_id, $parent_id,
    $last_pin_timestamp, $rtc_region, $permissions;
    public int $type, $position, $bitrate, $user_limit, $rate_limit_per_user, $video_quality_mode,
    $message_count, $member_count, $default_auto_archive_duration, $flags, $total_message_sent,
    $default_thread_rate_limit_per_user, $default_sort_order, $default_forum_layout;
    public object $permission_overwrites, $available_tags, $applied_tags;
    public array $recipients, $thread_metadata, $member, $default_reaction_emoji;
    public bool $nsfw, $managed;

    public object $invites, $invite, $followed_channel;

    private function typeConverter(string $type){
        $array = [
            "GUILD_TEXT" => 0,
            "DM" => 1,
            "GUILD_VOICE" => 2,
            "GROUP_DM" => 3,
            "GUILD_CATEGORY" => 4,
            "GUILD_ANNOUNCEMENT" => 5,
            "ANNOUNCEMENT_THREAD" => 10,
            "PUBLIC_THREAD" => 11,
            "PRIVATE_THREAD" => 12,
            "GUILD_STAGE_VOICE" => 13,
            "GUILD_DIRECTORY" => 14,
            "GUILD_FORUM" => 15,
            "GUILD_MEDIA" => 16
        ];

        if(isset($array[$type])){
            return $array[$type];
        }
    }

    private function videoQualityModeConverter(string $mode){
        $array = [
            "AUTO" => 1,
            "FULL" => 2
        ];

        if(isset($array[$mode])){
            return $array[$mode];
        }
    }

    private function sortOrderTypeConverter(string $type){
        $array = [
            "LATEST_ACTIVITY" => 0,
            "CREATION_DATE" => 1
        ];

        if(isset($array[$type])){
            return $array[$type];
        }
    }

    private function forumLayoutTypeConverter(string $type){
        $array = [
            "NOT_SET" => 0,
            "LIST_VIEW" => 1,
            "GALLERY_VIEW" => 2
        ];

        if(isset($array[$type])){
            return $array[$type];
        }
    }

    public function get(string $channel_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "position", "permission_overwrites", "name", "topic", "nsfw", "last_message_id",
            "bitrate", "user_limit", "rate_limit_per_user", "recipients", "icon", "owner_id", "application_id", "managed",
            "parant_id", "last_pin_timestamp", "rtc_region", "video_quality_mode", "message_count", "member_count",
            "thread_metadata", "member", "default_auto_archive_duration", "permissions", "flags", "total_message_sent",
            "available_tags", "applied_tags", "default_reaction_emoji", "default_thread_rate_limit_per_user",
            "default_sort_order", "default_forum_layout"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function edit_group_dm(string $channel_id, ?string $audit_log_reason, string $name, string $icon){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $data = [
            "name" => $name,
            "icon" => $icon
        ];

        $browser->patch($url, $header, $data)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "position", "permission_overwrites", "name", "topic", "nsfw", "last_message_id",
            "bitrate", "user_limit", "rate_limit_per_user", "recipients", "icon", "owner_id", "application_id", "managed",
            "parant_id", "last_pin_timestamp", "rtc_region", "video_quality_mode", "message_count", "member_count",
            "thread_metadata", "member", "default_auto_archive_duration", "permissions", "flags", "total_message_sent",
            "available_tags", "applied_tags", "default_reaction_emoji", "default_thread_rate_limit_per_user",
            "default_sort_order", "default_forum_layout"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function edit_guild_channel(string $channel_id, ?string $audit_log_reason, string $name, int|string $type=Null, ?int $position,
    ?string $topic, ?bool $nsfw, ?int $rate_limit_per_user, ?int $bitrate, ?int $user_limit, ?Channel\Overwrite $permission_overwrites,
    ?string $parent_id, ?string $rtc_region, int|string $video_quality_mode=Null, ?int $default_auto_archive_duration,
    ?int $flags, ?Channel\ForumTag $available_tags, ?Channel\DefaultReaction $default_reaction_emoji, ?int $default_thread_rate_limit_per_user,
    int|string $default_sort_order=Null, int|string $default_forum_layout=Null){

        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        if(gettype($type)=="string"){
            $type = $this->typeConverter($type);
        }

        if(gettype($video_quality_mode)=="string"){
            $video_quality_mode = $this->videoQualityModeConverter($video_quality_mode);
        }

        if(gettype($default_sort_order)=="string"){
            $default_sort_order = $this->sortOrderTypeConverter($default_sort_order);
        }

        if(gettype($default_forum_layout)=="string"){
            $default_forum_layout = $this->forumLayoutTypeConverter($default_forum_layout);
        }

        $data = [
            "name" => $name,
            "type" => $type,
            "position" => $position,
            "topic" => $topic,
            "nsfw" => $nsfw,
            "rate_limit_per_user" => $rate_limit_per_user,
            "bitrate" => $bitrate,
            "user_limit" => $user_limit,
            "permission_overwrites" => $permission_overwrites->set_overwrite(),
            "parent_id" => $parent_id,
            "rtc_region" => $rtc_region,
            "video_quality_mode" => $video_quality_mode,
            "default_auto_archive_duration" => $default_auto_archive_duration
        ];

        $dataList = [
            "flags" => $flags,
            "available_tags" => $available_tags->set_forumtag(),
            "default_reaction_emoji" => $default_reaction_emoji->set_defaultReaction(),
            "default_thread_rate_limit_per_user" => $default_thread_rate_limit_per_user,
            "default_sort_order" => $default_sort_order,
            "default_forum_layout" => $default_forum_layout
        ];
        $dataKeyList = ["flags", "available_tags", "default_reaction_emoji", "default_thread_rate_limit_per_user",
        "default_sort_order", "default_forum_layout"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $json = json_encode($data);

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "position", "permission_overwrites", "name", "topic", "nsfw", "last_message_id",
            "bitrate", "user_limit", "rate_limit_per_user", "recipients", "icon", "owner_id", "application_id", "managed",
            "parant_id", "last_pin_timestamp", "rtc_region", "video_quality_mode", "message_count", "member_count",
            "thread_metadata", "member", "default_auto_archive_duration", "permissions", "flags", "total_message_sent",
            "available_tags", "applied_tags", "default_reaction_emoji", "default_thread_rate_limit_per_user",
            "default_sort_order", "default_forum_layout"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function edit_thread(string $channel_id, ?string $audit_log_reason, string $name, bool $archived, int $auto_archive_duration,
    bool $locked, bool $invitable, ?int $rate_limit_per_user, ?int $flags, ?array $applied_tags){
        
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;
    
        $data = [
            "name" => $name,
            "archived" => $archived,
            "auto_archive_duration" => $auto_archive_duration,
            "locked" => $locked,
            "invitable" => $invitable,
            "rate_limit_per_user" => $rate_limit_per_user
        ];

        $dataList = [
            "flags" => $flags,
            "applied_tags" => $applied_tags
        ];
        $dataKeyList = ["flags", "applied_tags"];
        
        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $json = json_encode($data);

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "position", "permission_overwrites", "name", "topic", "nsfw", "last_message_id",
            "bitrate", "user_limit", "rate_limit_per_user", "recipients", "icon", "owner_id", "application_id", "managed",
            "parant_id", "last_pin_timestamp", "rtc_region", "video_quality_mode", "message_count", "member_count",
            "thread_metadata", "member", "default_auto_archive_duration", "permissions", "flags", "total_message_sent",
            "available_tags", "applied_tags", "default_reaction_emoji", "default_thread_rate_limit_per_user",
            "default_sort_order", "default_forum_layout"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function delete(string $channel_id, ?string $audit_log_reason){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason
        ];

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function delete_bulk(string $channel_id, array $messages, ?string $audit_log_reason){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/messages/bulk-delete";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $data = [
            "messages" => $messages
        ];

        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function edit_channel_permissions(string $channel_id, string $overwrite_id, 
    int|string $type, ?string $audit_log_reason, ?string $allow="0", ?string $deny="0"){

        if(gettype($type)=="string"){
            if($type == "role"){
                $type = 0;
            } else if($type == "member"){
                $type = 1;
            }
        }

        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/permissions/" . $overwrite_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $data = [
            "allow" => $allow,
            "deny" => $deny,
            "type" => $type
        ];

        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function get_channel_invites(string $channel_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/invites";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $invites = json_decode($response->getBody());
            $this->invites = $invites;
        });
    }

    public function create_channel_invite(string $channel_id, ?string $audit_log_reason,
    ?int $max_age=86400, ?int $max_uses=0, ?bool $temporary=False, ?bool $unique=False,
    ?int $target_type, ?string $target_user_id, ?string $target_application_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [
            "max_age" => $max_age,
            "max_uses" => $max_uses,
            "temporary" => $temporary,
            "unique" => $unique
        ];

        $dataList = [
            "target_type" => $target_type,
            "target_user_id" => $target_user_id,
            "target_application_id" => $target_application_id
        ];
        $dataKeyList = ["target_type", "target_user_id", "target_application_id"];
        
        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $json = json_encode($data);

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/invites";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json",
            "X-Audit-Log-Reason" => $audit_log_reason
        ];

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $invite = json_decode($response->getBody());
            $this->invite = $invite;
        });
    }

    public function delete_channel_permission(string $channel_id, string $overwrite_id, ?string $audit_log_reason){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/permissions/" . $overwrite_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason
        ];

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function follow_announcement_channel(string $channel_id, string $webhook_channel_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/followers";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $data = [
            "webhook_channel_id" => $webhook_channel_id
        ];

        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $followed_channel = json_decode($response->getBody());
            $this->followed_channel = $followed_channel;
        });
    }

    public function create_thread_from_message(string $channel_id, string $message_id, string $name, ?string $audit_log_reason,
    ?int $auto_archive_duration, ?int $rate_limit_per_user){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [
            "name" => $name,
            "rate_limit_per_user" => $rate_limit_per_user
        ];

        $dataList = [
            "auto_archive_duration" => $auto_archive_duration
        ];
        $dataKeyList = ["auto_archive_duration"];
        
        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/messages/" . $message_id . "/threads";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json",
            "X-Audit-Log-Reason" => $audit_log_reason
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $channel = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "position", "permission_overwrites", "name", "topic", "nsfw", "last_message_id",
            "bitrate", "user_limit", "rate_limit_per_user", "recipients", "icon", "owner_id", "application_id", "managed",
            "parant_id", "last_pin_timestamp", "rtc_region", "video_quality_mode", "message_count", "member_count",
            "thread_metadata", "member", "default_auto_archive_duration", "permissions", "flags", "total_message_sent",
            "available_tags", "applied_tags", "default_reaction_emoji", "default_thread_rate_limit_per_user",
            "default_sort_order", "default_forum_layout"];
            foreach($keyList as $key){
                if(isset($channel->$key)){
                    $this->$key = $channel->$key;
                } else {
                    $this->$key = Null;
                }
            }
        });
    }

    public function create_thread_without_message(string $channel_id, string $name, ?string $audit_log_reason,
    ?int $auto_archive_duration, int|string $type=12, ?bool $invitable, ?int $rate_limit_per_user){
        
        $threadTypes = [
            "ANNOUNCEMENT_THREAD" => 10,
            "PUBLIC_THREAD" => 11,
            "PRIVATE_THREAD" => 12
        ];
        
        $typeList = ["ANNOUNCEMENT_THREAD", "PUBLIC_THREAD", "PRIVATE_THREAD"];

        if(gettype($type)=="string"){
            foreach($typeList as $threadType){
                if($threadType == $type){
                    $type = $threadTypes[$threadType];
                }
            }
        }

        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [
            "name" => $name,
            "rate_limit_per_user" => $rate_limit_per_user
        ];

        $dataList = [
            "auto_archive_duration" => $auto_archive_duration,
            "type" => $type,
            "invitable" => $invitable
        ];
        $dataKeyList = ["auto_archive_duration", "type", "invitable"];
        
        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/threads";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json",
            "X-Audit-Log-Reason" => $audit_log_reason
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $channel = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "position", "permission_overwrites", "name", "topic", "nsfw", "last_message_id",
            "bitrate", "user_limit", "rate_limit_per_user", "recipients", "icon", "owner_id", "application_id", "managed",
            "parant_id", "last_pin_timestamp", "rtc_region", "video_quality_mode", "message_count", "member_count",
            "thread_metadata", "member", "default_auto_archive_duration", "permissions", "flags", "total_message_sent",
            "available_tags", "applied_tags", "default_reaction_emoji", "default_thread_rate_limit_per_user",
            "default_sort_order", "default_forum_layout"];
            foreach($keyList as $key){
                if(isset($channel->$key)){
                    $this->$key = $channel->$key;
                } else {
                    $this->$key = Null;
                }
            }
        });
    }

    public function create_thread_in_forum_or_media_channel(string $channel_id, string $name, ?string $audit_log_reason,
    ?int $auto_archive_duration, ?int $rate_limit_per_user, ?array $applied_tags,
    ?string $content, ?Channel\Message\Embeds $embeds, ?Channel\Message\AllowedMentions $allowed_mentions,
    ?Channel\Message\Components $components, ?array $sticker_ids, ?Channel\Message\Attachments $attachments, 
    ?int $flags){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $message = [];

        $arrayList = [
            "content" => $content,
            "embeds" => $embeds,
            "allowed_mentions" => $allowed_mentions,
            "components" => $components,
            "sticker_ids" => $sticker_ids,
            "attachments" => $attachments,
            "flags" => $flags
        ];
        $arrayKeyList = ["content", "embeds", "allowed_mentions", "components",
        "sticker_ids", "attachments", "flags"];
        
        foreach($arrayKeyList as $arrayKey){
            if($arrayList[$arrayKey] != Null){
                $newArray = [
                    $arrayKey => $arrayList[$arrayKey]
                ];
                $message = array_merge($message, $newArray);
            }
        }

        $data = [
            "name" => $name,
            "rate_limit_per_user" => $rate_limit_per_user,
            "message" => $message
        ];

        $dataList = [
            "auto_archive_duration" => $auto_archive_duration,
            "applied_tags" => $applied_tags
        ];
        $dataKeyList = ["auto_archive_duration", "applied_tags"];
        
        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/threads";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json",
            "X-Audit-Log-Reason" => $audit_log_reason
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $channel = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "position", "permission_overwrites", "name", "topic", "nsfw", "last_message_id",
            "bitrate", "user_limit", "rate_limit_per_user", "recipients", "icon", "owner_id", "application_id", "managed",
            "parant_id", "last_pin_timestamp", "rtc_region", "video_quality_mode", "message_count", "member_count",
            "thread_metadata", "member", "default_auto_archive_duration", "permissions", "flags", "total_message_sent",
            "available_tags", "applied_tags", "default_reaction_emoji", "default_thread_rate_limit_per_user",
            "default_sort_order", "default_forum_layout", "message"];
            foreach($keyList as $key){
                if(isset($channel->$key)){
                    $this->$key = $channel->$key;
                } else {
                    $this->$key = Null;
                }
            }
        });
    }

    public function join_thread_bot(string $channel_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/thread-members/@me";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->put($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function add_thread_member(string $channel_id, string $user_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/thread-members/" . $user_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->put($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function leave_thread_bot(string $channel_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/thread-members/@me";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function remove_thread_member(string $channel_id, $user_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/thread-members/" . $user_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->put($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }



    public function get_guild_channels(string $guild_id){
        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/channels";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = $response->getStatusCode();
            return $data;
        });
    }

    public function create_guild_channel(string $guild_id, ?string $audit_log_reason,
    string $name, int|string $type=Null, ?string $topic, ?int $bitrate, ?int $user_limit,
    ?int $rate_limit_per_user, ?int $position, ?array $permission_overwrites,
    ?string $parent_id, ?bool $nsfw, ?string $rtc_region, ?int $video_quality_mode,
    ?int $default_auto_archive_duration, ?\Discord\Channel\DefaultReaction $default_reaction_emoji,
    ?array $available_tags, ?int $default_sort_order, ?int $default_forum_layout,
    ?int $default_thread_rate_limit_per_user){

        $loader = new ConfigLoad();
        $loader->load();
        $token = $loader->token;

        if(gettype($type)=="string"){
            $type = $this->typeConverter($type);
        }

        $data = [
            "name" => $name
        ];

        $dataList = [
            "type" => $type,
            "topic" => $topic,
            "bitrate" => $bitrate,
            "user_limit" => $user_limit,
            "rate_limit_per_user" => $rate_limit_per_user,
            "position" => $position,
            "permission_overwrites" => $permission_overwrites,
            "parent_id" => $parent_id,
            "nsfw" => $nsfw,
            "rtc_region" => $rtc_region,
            "video_quality_mode" => $video_quality_mode,
            "default_auto_archive_duration" => $default_auto_archive_duration,
            "default_reaction_emoji" => $default_reaction_emoji,
            "available_tags" => $available_tags,
            "default_sort_order" => $default_sort_order,
            "default_forum_layout" => $default_forum_layout,
            "default_thread_rate_limit_per_user" => $default_thread_rate_limit_per_user
        ];
        $dataKeyList = ["type", "topic", "bitrate", "user_limit", "rate_limit_per_user", "position",
        "permission_overwrites", "parent_id", "nsfw", "rtc_region", "video_quality_mode",
        "default_auto_archive_duration", "default_reaction_emoji", "available_tags",
        "default_sort_order", "default_forum_layout", "default_thread_rate_limit_per_user"];

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
            "X-Audit-Log-Reason" => $audit_log_reason,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "position", "permission_overwrites", "name", "topic", "nsfw", "last_message_id",
            "bitrate", "user_limit", "rate_limit_per_user", "recipients", "icon", "owner_id", "application_id", "managed",
            "parant_id", "last_pin_timestamp", "rtc_region", "video_quality_mode", "message_count", "member_count",
            "thread_metadata", "member", "default_auto_archive_duration", "permissions", "flags", "total_message_sent",
            "available_tags", "applied_tags", "default_reaction_emoji", "default_thread_rate_limit_per_user",
            "default_sort_order", "default_forum_layout", "message"];
            foreach($keyList as $key){
                if(isset($data->$key)){
                    $this->$key = $data->$key;
                } else {
                    $this->$key = Null;
                }
            }
        });
    }

}


?>