<?php

namespace Discord\Interaction\Data;

use Psr;
use React;
require "../vendor/autoload.php";

class ModalSubmitData{
    public string $custom_id;
    public array $components;
}

?>