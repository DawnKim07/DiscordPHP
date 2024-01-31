<?php

namespace Discord\Interaction\Data;

use Psr;
use React;
require "../vendor/autoload.php";

class ResolvedData{
    public array $users, $members, $roles, $channels, $messages, $attachments;
}

?>