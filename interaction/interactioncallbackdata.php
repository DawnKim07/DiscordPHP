<?php

namespace Discord\Interaction\Response;

use Psr;
use React;
require "../vendor/autoload.php";

class Messages{
    public bool $tts;
    public string $content;
    public array $embeds, $components, $attachments;
    public object $allowed_mentions;
    public int $flags;
}

class AutoComplete{
    public array $choices;
}

class Modal{
    public string $custom_id, $title;
    public array $components;
}

?>