<?php

namespace Discord\Invite;

class InviteMetadata{
    public int $uses, $max_uses, $max_age;
    public string $created_at;
    public bool $temporary;
}

?>