<?php

namespace Discord;

use Psr;
use React;
require "../vendor/autoload.php";

class MessageInteraction{
    public string $id, $name;
    public int $type;
    public object $user, $member;
}

?>