<?php

namespace Discord\Channel\Message;

class Components{

    private array $components_container;

    public function add(?Components\Buttons $buttons, ?Components\SelectMenus $select_menus, ?Components\TextInputs $text_inputs){

        if(!isset($this->components_container)){
            $this->components_container = [];
        }

        $subcomponents_container = array_merge($buttons->set_buttons(), $select_menus->set_selectmenus(), $text_inputs->set_textinputs());

        $components = [
            "type" => 1,
            "components" => $subcomponents_container
        ];

        array_push($this->components_container, $components);
    }

    public function set_components(){
        if(!isset($this->components_container)){
            $this->components_container = Null;
        }

        return $this->components_container;
    }

    public function get(\Discord\Channel\Message $message, ?int $index){
        if(!isset($message->components)){
            return Null;
        }

        if($index == Null){
            return $message->components[0]->components;
        } else {
            return $message->components[$index]->components;
        }

    }
}

?>