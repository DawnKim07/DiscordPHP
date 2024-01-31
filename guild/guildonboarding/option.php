<?php

namespace Discord\Guild\GuildOnboarding\Prompt;

class GuildPreview{
    public string $id, $emoji_id, $emoji_name, $title, $description;
    public array $channel_ids, $role_ids;
    public object $emoji;
    public bool $emoji_animated;

}

?>