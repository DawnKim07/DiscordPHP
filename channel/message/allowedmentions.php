<?php

namespace Discord\Channel\Message;

class AllowedMentions{
    public array $parse, $roles, $users;
    public bool $replied_user;

    private array $allowedmentions;

    public function set(array $parse, array $roles, array $users, bool $replied_user){

        $allowedmentions = [
            "parse" => $parse,
            "roles" => $roles,
            "users" => $users,
            "replied_user" => $replied_user
        ];

        $this->allowedmentions = $allowedmentions;
    }

    public function set_allowedmentions(){
        if(!isset($this->allowedmentions)){
            $this->allowedmentions = Null;
        }

        return $this->allowedmentions;
    }
}

?>