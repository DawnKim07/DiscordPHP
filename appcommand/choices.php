<?php

namespace Discord\AppCommand\Options;

class Choices{
    public string $name;
    public array $name_localizations;
    public $value;

    private array $choices_container;

    public function add(string $name, ?array $name_localizations, string|int|float $value){
        if(!isset($this->choices_container)){
            $this->choices_container = [];
        }
        
        $choices = [
            "name" => $name,
            "name_localizations" => $name_localizations,
            "value" => $value
        ];

        array_push($this->choices_container, $choices);
    }

    public function set_choices(){
        if(!isset($this->choices_container)){
            $this->choices_container = Null;
        }

        return $this->choices_container;
    }

    public function get(\Discord\AppCommand\Options $options, ?int $index){
        if(!isset($options->choices)){
            return Null;
        }

        if($index == Null){
            return $options->choices;
        } else {
            $choice = $options->choices[$index];

            $keyList = ["name", "name_localizations", "value"];
            foreach($keyList as $key){
                if(isset($choice->$key)){
                    $this->$key = $choice->$key;
                } else {
                    $this->$key = Null;
                }
            }
        }
    }
}
?>