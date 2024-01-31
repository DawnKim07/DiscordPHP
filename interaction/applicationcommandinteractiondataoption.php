<?php

namespace Discord\Interaction\Data\ApplicationCommandData;

use Psr;
use React;
require "../vendor/autoload.php";

class Option{
    public string $name;
    public int $type;
    public mixed $value;
    public array $options;
    public bool $focused;
}

?>