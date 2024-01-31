<?php

namespace Discord;

class ConfigLoad{
    public $token, $app_id, $guild_id, $channel_id;

    function load(){
        $JSON = json_decode(file_get_contents(__DIR__ . '/config.json'));
        $this->token = $JSON->bot_token;
        $this->app_id = $JSON->bot_id;
        $this->guild_id = $JSON->guild_id;
        $this->channel_id = $JSON->channel_id;
    }
}

?>