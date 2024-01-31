<?php

namespace Discord\AuditLog\Entry;

use Psr;
use React;
require "../vendor/autoload.php";

class OptionalInfo{

    public string $application_id, $auto_moderation_rule_name, $auto_moderation_rule_trigger_type,
    $channel_id, $count, $delete_member_days, $id, $members_removed, $message_id, $role_name, $type,
    $integration_type;

}

?>