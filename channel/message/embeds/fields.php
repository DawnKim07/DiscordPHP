<?php

namespace Discord\Channel\Message\Embeds;

class Fields{
    public string $name, $value;
    public bool $inline;

    private array $fields_container;

    public function add(string $name, string $value, ?bool $inline){
        if(!isset($this->fields_container)){
            $this->fields_container = [];
        }

        $field = [
            "name" => $name,
            "value" => $value
        ];

        $dataList = [
            "inline" => $inline
        ];
        $dataKeyList = ["inline"];
        
        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $field = array_merge($field, $newData);
            }
        }

        array_push($this->fields_container, $field);
    }

    public function set_fields(){
        if(!isset($this->fields_container)){
            $this->fields_container = Null;
        }

        return $this->fields_container;
    }

    public function get(\Discord\Channel\Message\Embeds $embeds, ?int $index){
        if(!isset($embeds->fields)){
            return Null;
        }

        if($index == Null){
            return $embeds->fields;
        } else {
            $field = $embeds->fields[$index];

            $keyList = ["name", "value", "inline"];
            foreach($keyList as $key){
                if(isset($field->$key)){
                    $this->$key = $field->$key;
                } else {
                    $this->$key = Null;
                }
            }
        }
    }
}

?>