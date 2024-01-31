<?php

namespace Discord\AuditLog;

use Psr;
use React;
require "../vendor/autoload.php";

class Entry{

    public string $target_id, $user_id, $id, $reason;
    public array $changes;
    public int $action_type;
    public object $options;

}

?>