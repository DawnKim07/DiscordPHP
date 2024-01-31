<?php

namespace Discord\Channel;

use React;
use Psr;
require __DIR__ . '/../../vendor/autoload.php';

class Message{
    public string $id, $channel_id, $content, $timestamp, $edited_timestamp, $nonce, $webhook_id, $application_id;
    public int $type, $flags, $position;
    public bool $tts, $mention_everyone, $pinned;
    
    public object $author, $mentions, $mention_roles, $mention_channels, $attachments, $embeds, $reactions,
    $activity, $application, $message_reference, $referenced_message, $interaction, $thread, $components,
    $sticker_items, $stickers, $role_subscription_data, $resolved;

    public function get(string $channel_id, string $message_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/messages/" . $message_id;
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "channel_id", "author", "content", "timestamp", "edited_timestamp", "tts",
            "mention_everyone", "mentions", "mention_roles", "mention_channels", "attachments",
            "attachments", "embeds", "reactions", "nonce", "pinned", "webhook_id", "type", "activity",
            "application", "application_id", "message_reference", "flags", "referenced_message",
            "interaction", "thread", "components", "sticker_items", "stickers", "position", "role_subscription_data",
            "resolved"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function create(string $channel_id, ?string $content, int|string $nonce=Null,
    ?bool $tts, ?Message\Embeds $embeds, ?Message\AllowedMentions $allowed_mentions, ?Message\Reference $message_reference,
    ?Message\Components $components, ?array $sticker_ids, ?Message\Attachments $attachments, ?int $flags){

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $data = [];

        $dataList = [
            "content" => $content,
            "nonce" => $nonce,
            "tts" => $tts,
            "embeds" => $embeds->set_embeds(),
            "allowed_mentions" => $allowed_mentions->set_allowedmentions(),
            "message_reference" => $message_reference->set_reference(),
            "components" => $components->set_components(),
            "sticker_ids" => $sticker_ids,
            "attachments" => $attachments->set_attachments_for_message(),
            "flags" => $flags
        ];
        $dataKeyList = ["content", "nonce", "tts", "embeds", "allowed_mentions", "message_reference",
        "components", "sticker_ids", "attachments", "flags"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/messages";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $browser = new React\Http\Browser();   
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "channel_id", "author", "content", "timestamp", "edited_timestamp", "tts",
            "mention_everyone", "mentions", "mention_roles", "mention_channels", "attachments",
            "attachments", "embeds", "reactions", "nonce", "pinned", "webhook_id", "type", "activity",
            "application", "application_id", "message_reference", "flags", "referenced_message",
            "interaction", "thread", "components", "sticker_items", "stickers", "position", "role_subscription_data",
            "resolved"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function create_bot_reaction(string $channel_id, string $message_id, string $emoji_name){
        
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $guild_id = $loader->guild_id;

        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/emojis";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser = new React\Http\Browser();
        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response) use($emoji_name, $channel_id, $message_id, $token){
            $data = json_decode($response->getBody());
            foreach($data as $emoji){
                if($emoji->name == $emoji_name){
                    $emoji_id = $emoji->id;

                    $emoji = http_build_query([
                        $emoji_name => $emoji_id
                    ]);

                    $url = "https://discord.com/api/v10/channels/" . $channel_id . "/messages/" . $message_id . "/reactions/" . $emoji . "/@me";
                    $header = [
                        "Authorization" => "Bot" . " " . $token
                    ];

                    $browser = new React\Http\Browser();
                    $browser->put($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
                        $code = $response->getStatusCode();
                        return $code;
                    }, function(React\Http\Message\ResponseException $e){
                        $errorData = json_decode($e->getResponse()->getBody());
                        return $errorData;
                    });

                    break;
                }
            }
        });
        
    }

    public function delete_bot_reaction(string $channel_id, string $message_id, string $emoji_name){
        
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $guild_id = $loader->guild_id;

        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/emojis";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser = new React\Http\Browser();
        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response) use($emoji_name, $channel_id, $message_id, $token){
            $data = json_decode($response->getBody());
            foreach($data as $emoji){
                if($emoji->name == $emoji_name){
                    $emoji_id = $emoji->id;

                    $emoji = http_build_query([
                        $emoji_name => $emoji_id
                    ]);

                    $url = "https://discord.com/api/v10/channels/" . $channel_id . "/messages/" . $message_id . "/reactions/" . $emoji . "/@me";
                    $header = [
                        "Authorization" => "Bot" . " " . $token
                    ];
                    
                    $browser = new React\Http\Browser();
                    $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
                        $code = $response->getStatusCode();
                        return $code;
                    }, function(React\Http\Message\ResponseException $e){
                        $errorData = json_decode($e->getResponse()->getBody());
                        return $errorData;
                    });

                    break;
                }
            }
        });
        
    }

    public function delete_user_reaction(string $channel_id, string $message_id, string $emoji_name, string $user_id){
        
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $guild_id = $loader->guild_id;

        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/emojis";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser = new React\Http\Browser();
        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response) use($emoji_name, $channel_id, $message_id, $token, $user_id){
            $data = json_decode($response->getBody());
            foreach($data as $emoji){
                if($emoji->name == $emoji_name){
                    $emoji_id = $emoji->id;

                    $emoji = http_build_query([
                        $emoji_name => $emoji_id
                    ]);

                    $url = "https://discord.com/api/v10/channels/" . $channel_id . "/messages/" . $message_id . "/reactions/" . $emoji . "/" . $user_id;
                    $header = [
                        "Authorization" => "Bot" . " " . $token
                    ];
                    
                    $browser = new React\Http\Browser();
                    $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
                        $code = $response->getStatusCode();
                        return $code;
                    }, function(React\Http\Message\ResponseException $e){
                        $errorData = json_decode($e->getResponse()->getBody());
                        return $errorData;
                    });

                    break;
                }
            }
        });
        
    }

    public function get_reactions(string $channel_id, string $message_id, string $emoji_name, ?string $after="absent", ?int $limit=25){
        
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $guild_id = $loader->guild_id;

        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/emojis";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser = new React\Http\Browser();
        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response) use($emoji_name, $channel_id, $message_id, $token, $after, $limit){
            $data = json_decode($response->getBody());
            foreach($data as $emoji){
                if($emoji->name == $emoji_name){
                    $emoji_id = $emoji->id;

                    $emoji = http_build_query([
                        $emoji_name => $emoji_id
                    ]);

                    $urlQuery = http_build_query([
                        "after" => $after,
                        "limit" => $limit
                    ]);

                    $url = "https://discord.com/api/v10/channels/" . $channel_id . "/messages/" . $message_id . "/reactions/" . $emoji . "?" . $urlQuery;
                    $header = [
                        "Authorization" => "Bot" . " " . $token
                    ];
                    
                    $browser = new React\Http\Browser();
                    $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
                        $userArray = json_decode($response->getBody());
                        return $userArray;

                    }, function(React\Http\Message\ResponseException $e){
                        $errorData = json_decode($e->getResponse()->getBody());
                        return $errorData;

                    });

                    break;
                }
            }
        });
        
    }

    public function delete_reactions_all(string $channel_id, string $message_id){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/messages/" . $message_id . "/reactions";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function delete_reactions_emoji(string $channel_id, string $message_id, string $emoji_name){
        
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;
        $guild_id = $loader->guild_id;

        $url = "https://discord.com/api/v10/guilds/" . $guild_id . "/emojis";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser = new React\Http\Browser();
        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response) use($emoji_name, $channel_id, $message_id, $token){
            $data = json_decode($response->getBody());
            foreach($data as $emoji){
                if($emoji->name == $emoji_name){
                    $emoji_id = $emoji->id;

                    $emoji = http_build_query([
                        $emoji_name => $emoji_id
                    ]);

                    $url = "https://discord.com/api/v10/channels/" . $channel_id . "/messages/" . $message_id . "/reactions/" . $emoji;
                    $header = [
                        "Authorization" => "Bot" . " " . $token
                    ];
                    
                    $browser = new React\Http\Browser();
                    $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
                        $code = $response->getStatusCode();
                        return $code;
                    }, function(React\Http\Message\ResponseException $e){
                        $errorData = json_decode($e->getResponse()->getBody());
                        return $errorData;
                    });

                    break;
                }
            }
        });
    }

    public function edit(string $channel_id, string $message_id, ?string $content, ?Message\Embeds $embeds, ?int $flags,
    ?Message\AllowedMentions $allowed_mentions, ?Message\Components $components, ?Message\Attachments $attachments){

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;
     
        $data = [
            "content" => $content,
            "embeds" => $embeds->set_embeds(),
            "flags" => $flags,
            "allowed_mentions" => $allowed_mentions->set_allowedmentions(),
            "componenets" => $components->set_components(),
            "attachments" => $attachments->set_attachments_for_message()
        ];
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/messages/" . $message_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser = new React\Http\Browser();   
        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "channel_id", "author", "content", "timestamp", "edited_timestamp", "tts",
            "mention_everyone", "mentions", "mention_roles", "mention_channels", "attachments",
            "attachments", "embeds", "reactions", "nonce", "pinned", "webhook_id", "type", "activity",
            "application", "application_id", "message_reference", "flags", "referenced_message",
            "interaction", "thread", "components", "sticker_items", "stickers", "position", "role_subscription_data",
            "resolved"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

    public function delete(string $channel_id, string $message_id, ?string $audit_log_reason){

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/messages/" . $message_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json",
            "X-Audit-Log-Reason" => $audit_log_reason
        ];

        $browser = new React\Http\Browser();
        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });

    }

    public function delete_bulk(string $channel_id, array $messages, ?string $audit_log_reason){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/messages/bulk-delete";
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json",
            "X-Audit-Log-Reason" => $audit_log_reason
        ];
        $data = [
            "messages" => $messages
        ];
        $json = json_encode($data);

        $browser = new React\Http\Browser();
        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        }, function(\Exception $e){
            $code = $e->getCode();
            return $code;
        });
    }

    public function get_pinned_message(string $channel_id, ?int $index=0){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/pins";
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response) use($index){
            $messages = json_decode($response->getBody());

            $message = $messages[$index];
            $keyList =["id", "channel_id", "author", "content", "timestamp", "edited_timestamp", "tts",
            "mention_everyone", "mentions", "mention_roles", "mention_channels", "attachments",
            "attachments", "embeds", "reactions", "nonce", "pinned", "webhook_id", "type", "activity",
            "application", "application_id", "message_reference", "flags", "referenced_message",
            "interaction", "thread", "components", "sticker_items", "stickers", "position", "role_subscription_data",
            "resolved"];
            foreach($keyList as $key){
                if(isset($message->$key)){
                    $this->$key = $message->$key;
                } else {
                    $this->$key = Null;
                }
            }
        });
    }

    public function unpin_message(string $channel_id, string $message_id, ?string $audit_log_reason){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/channels/" . $channel_id . "/pins/" . $message_id;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "X-Audit-Log-Reason" => $audit_log_reason
        ];

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });
    }

    public function execute_webhook(string $webhook_id, string $webhook_token, ?bool $wait,
    ?string $thread_id, ?string $content, ?string $username, ?string $avatar_url, ?bool $tts,
    ?array $embeds, ?\Discord\Channel\Message\AllowedMentions $allowed_mentions, ?array $components,
    ?int $flags, ?string $thread_name, ?array $applied_tags){

        $data = [];

        $dataList = [
            "content" => $content, 
            "username" => $username, 
            "avatar_url" => $avatar_url, 
            "tts" => $tts, 
            "embeds" => $embeds, 
            "allowed_mentions" => $allowed_mentions,
            "components" => $components, 
            "flags" => $flags, 
            "thread_name" => $thread_name, 
            "applied_tags" => $applied_tags
        ];
        $dataKeyList = ["content", "username", "avatar_url", "tts", "embeds", "allowed_mentions",
        "components", "attachments", "flags", "thread_name", "applied_tags"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $QueryData = [];
        $QueryBase = [
            "wait" => $wait,
            "thread_id" => $thread_id
        ];
        $QueryKeyList = ["wait", "thread_id"];
        foreach($QueryKeyList as $key){
            if(isset($QueryBase[$key])){
                $QueryNewData = [
                    $key => $QueryBase[$key]
                ];
                $QueryData = array_merge($QueryData, $QueryNewData);
            }
        }

        $query = http_build_query($QueryData);

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/webhooks/" . $webhook_id . "/" . $webhook_token
         . "?" . $query;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "channel_id", "user", "name", "avatar", "token",
            "application_id", "source_guild", "source_channel", "url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
            $code = $response->getStatusCode();
            return $code;
        });

    }

    public function execute_webhook_with_files(string $webhook_id, string $webhook_token, ?bool $wait,
    ?string $thread_id, ?string $content, ?string $username, ?string $avatar_url, ?bool $tts,
    ?array $embeds, ?\Discord\Channel\Message\AllowedMentions $allowed_mentions, ?array $components,
    ?array $files, ?array $attachments, ?int $flags, ?string $thread_name, ?array $applied_tags){

        $data = [];

        $dataList = [
            "content" => $content, 
            "username" => $username, 
            "avatar_url" => $avatar_url, 
            "tts" => $tts, 
            "embeds" => $embeds, 
            "allowed_mentions" => $allowed_mentions,
            "components" => $components, 
            "attachments" => $attachments, 
            "flags" => $flags, 
            "thread_name" => $thread_name, 
            "applied_tags" => $applied_tags
        ];
        $dataKeyList = ["content", "username", "avatar_url", "tts", "embeds", "allowed_mentions",
        "components", "attachments", "flags", "thread_name", "applied_tags"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $data = array_merge($data, $newData);
            }
        }

        $UID = com_create_guid();

        $ExtFunc = new \ExtFunction;
        $body = $ExtFunc->build_multipart_body($UID, $data, $files);

        /*
        Variable '$files' must be given as form below(See \ExtFunction for detailed codes):
        
        $files = [
            [
                "filepath" => "FILEPATH",
                "content-type" => "CONTENTTYPE"
            ],
            
            # ... and so on for multiple files
        ];
        */

        $QueryData = [];
        $QueryBase = [
            "wait" => $wait,
            "thread_id" => $thread_id
        ];
        $QueryKeyList = ["wait", "thread_id"];
        foreach($QueryKeyList as $key){
            if(isset($QueryBase[$key])){
                $QueryNewData = [
                    $key => $QueryBase[$key]
                ];
                $QueryData = array_merge($QueryData, $QueryNewData);
            }
        }

        $query = http_build_query($QueryData);

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/webhooks/" . $webhook_id . "/" . $webhook_token
         . "?" . $query;
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "multipart/form-data; boundary=--$UID"
        ];

        $browser->post($url, $header, $body)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "channel_id", "user", "name", "avatar", "token",
            "application_id", "source_guild", "source_channel", "url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
            $code = $response->getStatusCode();
            return $code;
        });

    }

    public function get_webhook_message(string $webhook_id, string $webhook_token, string $message_id,
    ?string $thread_id){

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/webhooks/" . $webhook_id . "/" . $webhook_token
            . "/messages/" . $message_id;

        if(isset($thread_id)){
            $query = "?thread_id=" . "$thread_id";
            $url = "https://discord.com/api/v10/webhooks/" . $webhook_id . "/" . $webhook_token
            . "/messages/" . $message_id . $query;
        }
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "channel_id", "user", "name", "avatar", "token",
            "application_id", "source_guild", "source_channel", "url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function edit_webhook_message(string $webhook_id, string $webhook_token, string $message_id,
    ?string $thread_id, ?string $content, ?array $embeds, ?\Discord\Channel\Message\AllowedMentions $allowed_mentions,
    ?array $components){
        
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
        $url = "https://discord.com/api/v10/webhooks/" . $webhook_id . "/" . $webhook_token
            . "/messages/" . $message_id;

        if(isset($thread_id)){
            $query = "?thread_id=" . "$thread_id";
            $url = "https://discord.com/api/v10/webhooks/" . $webhook_id . "/" . $webhook_token
            . "/messages/" . $message_id . $query;
        }
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "channel_id", "user", "name", "avatar", "token",
            "application_id", "source_guild", "source_channel", "url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function edit_webhook_message_with_files(string $webhook_id, string $webhook_token, string $message_id,
    ?string $thread_id, ?string $content, ?array $embeds, ?\Discord\Channel\Message\AllowedMentions $allowed_mentions,
    ?array $components, ?array $files, ?array $attachments){
        
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

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

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/webhooks/" . $webhook_id . "/" . $webhook_token
            . "/messages/" . $message_id;

        if(isset($thread_id)){
            $query = "?thread_id=" . "$thread_id";
            $url = "https://discord.com/api/v10/webhooks/" . $webhook_id . "/" . $webhook_token
            . "/messages/" . $message_id . $query;
        }

        $UID = com_create_guid();
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "multipart/form-data; boundary=--$UID"
        ];

        $ExtFunc = new \ExtFunction;
        $body = $ExtFunc->build_multipart_body($UID, $data, $files);

        $browser->patch($url, $header, $body)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "channel_id", "user", "name", "avatar", "token",
            "application_id", "source_guild", "source_channel", "url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function delete_webhook_message(string $webhook_id, string $webhook_token, string $message_id,
    ?string $thread_id){

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/webhooks/" . $webhook_id . "/" . $webhook_token
            . "/messages/" . $message_id;

        if(isset($thread_id)){
            $query = "?thread_id=" . "$thread_id";
            $url = "https://discord.com/api/v10/webhooks/" . $webhook_id . "/" . $webhook_token
            . "/messages/" . $message_id . $query;
        }
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });

    }

    public function create_followup_message(string $application_id, string $interaction_token,
    ?string $thread_id, ?string $content, ?string $username, ?string $avatar_url, ?bool $tts,
    ?array $embeds, ?\Discord\Channel\Message\AllowedMentions $allowed_mentions, ?array $components,
    ?int $flags, ?string $thread_name, ?array $applied_tags){

        $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/" . $interaction_token;

        if(isset($thread_id)){
            $query = "?wait=true&thread_id=" . "$thread_id";
            $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/" . $interaction_token
            . $query;
        }

        $data = [];

        $dataList = [
            "content" => $content,
            "username" => $username,
            "avatar_url" => $avatar_url,
            "tts" => $tts,
            "embeds" => $embeds,
            "allowed_mentions" => $allowed_mentions,
            "components" => $components,
            "flags" => $flags,
            "thread_name" => $thread_name,
            "applied_tags" => $applied_tags
        ];
        $dataKeyList = ["content", "username", "avatar_url", "tts", "embeds", "allowed_mentions", 
        "components", "flags", "thread_name", "applied_tags"];

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
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->post($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "channel_id", "user", "name", "avatar", "token",
            "application_id", "source_guild", "source_channel", "url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function create_followup_message_with_files(string $application_id, string $interaction_token,
    ?string $thread_id, ?string $content, ?string $username, ?string $avatar_url, ?bool $tts,
    ?array $embeds, ?\Discord\Channel\Message\AllowedMentions $allowed_mentions, ?array $components,
    ?array $files, ?array $attachments, ?int $flags, ?string $thread_name, ?array $applied_tags){

        $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/" . $interaction_token;

        if(isset($thread_id)){
            $query = "?wait=true&thread_id=" . "$thread_id";
            $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/" . $interaction_token
            . $query;
        }

        $data = [];

        $dataList = [
            "content" => $content,
            "username" => $username,
            "avatar_url" => $avatar_url,
            "tts" => $tts,
            "embeds" => $embeds,
            "allowed_mentions" => $allowed_mentions,
            "components" => $components,
            "flags" => $flags,
            "thread_name" => $thread_name,
            "applied_tags" => $applied_tags,
            "attachments" => $attachments
        ];
        $dataKeyList = ["content", "username", "avatar_url", "tts", "embeds", "allowed_mentions", 
        "components", "flags", "thread_name", "applied_tags", "attachments"];

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

        $UID = com_create_guid();

        $browser = new React\Http\Browser();
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "multipart/form-data; boundary=--$UID"
        ];

        $ExtFunc = new \ExtFunction;
        $body = $ExtFunc->build_multipart_body($UID, $data, $files);

        $browser->post($url, $header, $body)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "channel_id", "user", "name", "avatar", "token",
            "application_id", "source_guild", "source_channel", "url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function get_followup_message(string $application_id, string $interaction_token,
    string $message_id, ?string $thread_id){

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/" . $interaction_token
        . "/messages/" . $message_id;

        if(isset($thread_id)){
            $query = "?thread_id=" . "$thread_id";
            $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/" . $interaction_token
            . "/messages/" . $message_id . $query;
        }

        $browser = new React\Http\Browser();
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "channel_id", "user", "name", "avatar", "token",
            "application_id", "source_guild", "source_channel", "url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function edit_followup_message(string $application_id, string $interaction_token,
    string $message_id, ?string $thread_id, ?string $content, ?array $embeds,
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

        $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/" . $interaction_token
        . "/messages/" . $message_id;

        if(isset($thread_id)){
            $query = "?thread_id=" . "$thread_id";
            $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/" . $interaction_token
            . "/messages/" . $message_id . $query;
        }

        $browser = new React\Http\Browser();
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "application/json"
        ];
        $json = json_encode($data);

        $browser->patch($url, $header, $json)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "channel_id", "user", "name", "avatar", "token",
            "application_id", "source_guild", "source_channel", "url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function edit_followup_message_with_files(string $application_id, string $interaction_token,
    string $message_id, ?string $thread_id, ?string $content, ?array $embeds,
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

        $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/" . $interaction_token
        . "/messages/" . $message_id;

        if(isset($thread_id)){
            $query = "?thread_id=" . "$thread_id";
            $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/" . $interaction_token
            . "/messages/" . $message_id . $query;
        }

        $UID = com_create_guid();

        $browser = new React\Http\Browser();
        $header = [
            "Authorization" => "Bot" . " " . $token,
            "Content-Type" => "multipart/form-data; boundary=--$UID"
        ];
        
        $ExtFunc = new \ExtFunction;
        $body = $ExtFunc->build_multipart_body($UID, $data, $files);

        $browser->patch($url, $header, $body)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "type", "guild_id", "channel_id", "user", "name", "avatar", "token",
            "application_id", "source_guild", "source_channel", "url"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });

    }

    public function delete_followup_message(string $application_id, string $interaction_token,
    string $message_id, ?string $thread_id){

        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/" . $interaction_token
        . "/messages/" . $message_id;

        if(isset($thread_id)){
            $query = "?thread_id=" . "$thread_id";
            $url = "https://discord.com/api/v10/webhooks/" . $application_id . "/" . $interaction_token
            . "/messages/" . $message_id . $query;
        }

        $browser = new React\Http\Browser();
        $header = [
            "Authorization" => "Bot" . " " . $token
        ];

        $browser->delete($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $code = $response->getStatusCode();
            return $code;
        });

    }

}

?>