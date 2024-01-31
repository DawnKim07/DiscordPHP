<?php

namespace Discord\Guild\GuildOnboarding;

class Prompt{
    public string $id, $title;
    public array $options;
    public int $type;
    public bool $single_select, $required, $in_onboarding;

}

?>