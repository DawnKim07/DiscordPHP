<?php

namespace Discord\Channel;

class Overwrite{
    public string $id, $allow, $deny;
    public int $type;

    private array $overwrite_container;

    public function add(string $id, int $type, string $allow, string $deny){
        if(!isset($this->overwrite_container)){
            $this->overwrite_container = [];
        }

        $overwrite = [
            "id" => $id,
            "type" => $type,
            "allow" => $allow,
            "deny" => $deny
        ];

        array_push($this->overwrite_container, $overwrite);
    }

    public function set_overwrite(){
        if(!isset($this->overwrite_container)){
            $this->overwrite_container = Null;
        }

        return $this->overwrite_container;
    }
}

?>