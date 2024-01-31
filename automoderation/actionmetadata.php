<?php

namespace Discord\AutoModeration\Action;

use Psr;
use React;
require "../vendor/autoload.php";

class Metadata{
    public string $channel_id, $custom_message;
    public int $duration_seconds;
}

?>