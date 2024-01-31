<?php

namespace Discord\AppCommand;

class Options{
    public int $type, $min_length, $max_length;
    public string $name, $description;
    public array $name_localizations, $description_localizations, $channel_types;
    public bool $required, $autocomplete;
    public $min_value, $max_value;

    public object $choices;
    private array $options_container;

    private function typeConverter(string $type){
        $array = [
            "SUB_COMMAND" => 1,
            "SUB_COMMAND_GROUP" => 2,
            "STRING" => 3,
            "INTEGER" => 4,
            "BOOLEAN" => 5,
            "USER" => 6,
            "CHANNEL" => 7,
            "ROLE" => 8,
            "MENTIONABLE" => 9,
            "NUMBER" => 10,
            "ATTACHMENT" => 11
        ];
        
        if(isset($array[$type])){
            return $array[$type];
        }
    }

    public function add(string|int $type, string $name, array $name_localizations, string $description,
    ?array $description_localizations, ?bool $required, ?Options\Choices $choices, ?Options $options,
    ?array $channel_types, int|float $min_value=Null, int|float $max_value=Null, ?int $min_length, ?int $max_length, ?bool $autocomplete){
        if(!isset($this->options_container)){
            $this->options_container = [];
        }

        if(gettype($type)=="string"){
            $type = $this->typeConverter($type);
        }

        $options = [
            "type" => $type,
            "name" => $name,
            "name_localizations" => $name_localizations,
            "description" => $description,
            "description_localizations" => $description_localizations,
            "required" => $required,
            "choices" => $choices->set_choices(),
            "options" => $options->set_embeds(),
            "channel_types" => $channel_types,
            "min_value" => $min_value,
            "max_value" => $max_value,
            "min_length" => $min_length,
            "max_length" => $max_length,
            "autocomplete" => $autocomplete
        ];

        array_push($this->options_container, $options);
    }

    public function set_embeds(){
        if(!isset($this->options_container)){
            $this->options_container = Null;
        }

        return $this->options_container;
    }

    public function get(\Discord\AppCommand $appcommand, ?int $index){
        if(!isset($appcommand->options)){
            return Null;
        }

        if($index == Null){
            return $appcommand->options;
        } else {
            $option = $appcommand->options[$index];

            $keyList = ["type", "name", "name_localizations", "description", "description_localizations",
            "required", "choices", "options", "channel_types", "min_value", "max_value", "min_length", "max_length",
            "autocomplete"];
            foreach($keyList as $key){
                if(isset($option->$key)){
                    $this->$key = $option->$key;
                } else {
                    $this->$key = Null;
                }
            }
        }
    }
}

?>