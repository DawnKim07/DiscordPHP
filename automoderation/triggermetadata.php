<?php

namespace Discord\AutoModeration\Rule;

use Psr;
use React;
require "../vendor/autoload.php";

class TriggerMetadata{
    public array $keyword_filter, $regex_patterns, $presets, $allow_list;
    public int $mention_total_limit;
    public bool $mention_raid_protection_enabled;
}

?>