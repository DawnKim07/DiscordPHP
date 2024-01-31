<?php

namespace Discord;

use Psr;
use React;
require "../vendor/autoload.php";

class Interaction{
    public string $id, $application_id, $guild_id, $channel_id, $token, $app_permission, $locale, $guild_locale;
    public int $type, $version;
    public object $data, $channel, $member, $user, $message;
    public array $entitlements;
}

?>