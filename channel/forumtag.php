<?php

namespace Discord\Channel;

class ForumTag{
    public string $id, $name, $emoji_id, $emoji_name;
    public bool $moderated;

    private array $forumtag_container;

    public function add(string $id, string $name, bool $moderated, ?string $emoji_id, ?string $emoji_name){
        if(!isset($this->forumtag_container)){
            $this->forumtag_container = [];
        }

        $forumTag = [
            "id" => $id,
            "name" => $name,
            "moderated" => $moderated,
            "emoji_id" => $emoji_id,
            "emoji_name" => $emoji_name
        ];

        array_push($this->forumtag_container, $forumTag);
    }

    public function set_forumtag(){
        if(!isset($this->forumtag_container)){
            $this->forumtag_container = Null;
        }

        return $this->forumtag_container;
    }
}

?>