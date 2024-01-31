<?php

namespace Discord\Channel;

class DefaultReaction{
    public string $emoji_id, $emoji_name;

    private array $defaultReaction;

    public function set(string $emoji_id, string $emoji_name){
        $defaultReaction = [
            "emoji_id" => $emoji_id,
            "emoji_name" => $emoji_name
        ];

        $this->defaultReaction = $defaultReaction;
    }

    public function set_defaultReaction(){
        if(!isset($this->defaultReaction)){
            $this->defaultReaction = Null;
        }

        return $this->defaultReaction;
    }

}

?>