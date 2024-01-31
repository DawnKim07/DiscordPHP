<?php

namespace Discord\AutoModeration;

use Psr;
use React;
require "../vendor/autoload.php";

class Action{
    public int $type;
    public object $metadata;
}

?>