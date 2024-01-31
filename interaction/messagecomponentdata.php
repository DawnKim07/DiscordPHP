<?php

namespace Discord\Interaction\Data;

use Psr;
use React;
require "../vendor/autoload.php";

class MessageComponentData{
    public string $custom_id;
    public int $component_type;
    public array $values;
    public object $resolved;
}

?>