<?php

namespace Discord\Interaction\Data;

use Psr;
use React;
require "../vendor/autoload.php";

class ApplicationCommandData{
    public string $id, $name, $guild_id, $target_id;
    public int $type;
    public object $resolved;
    public array $options;
}

?>