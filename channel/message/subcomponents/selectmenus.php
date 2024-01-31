<?php

namespace Discord\Channel\Message\Components;

class SelectMenus{

    private array $options, $default_values;

    private array $selectmenus_container;

    private function typeConverter(string $type){
        $typeList = [
            "String" => 3,
            "User" => 5,
            "Role" => 6,
            "Mentionable" => 7,
            "Channel" => 8
        ];

        if(isset($typeList[$type])){
            return $typeList[$type];
        } else {
            return Null;
        }
    }

    public function set_options(string $label, string $value, ?string $description,
    ?\Discord\Emoji $emoji, ?bool $default){

        $options = [
            "label" => $label,
            "value" => $value
        ];

        $dataList = [
            "description" => $description,
            "emoji" => $emoji->set_emoji_for_components(),
            "default" => $default
        ];
        $dataKeyList = ["description", "emoji", "default"];
        
        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $options = array_merge($options, $newData);
            }
        }

        $this->options = $options;

    }

    public function set_default_values(string $id, string $type){

        $default_values = [
            "id" => $id,
            "type" => $type
        ];

        $this->default_values = $default_values;

    }



    public function set(int|string $type, string $custom_id, ?array $channel_types,
    ?string $placeholder, ?int $min_values=1, ?int $max_values=1, ?bool $disabled=False){

        if(!isset($this->selectmenus_container)){
            $this->selectmenus_container = [];
        }

        if(gettype($type)=="string"){
            $type = $this->typeConverter($type);
        }

        $selectmenu = [
            "type" => $type,
            "custom_id" => $custom_id,
            "disabled" => $disabled
        ];

        if($type == 3){
            $dataList = [
                "options" => $this->options,
                "placeholder" => $placeholder,
                "min_values" => $min_values,
                "max_values" => $max_values,
            ];

            $dataKeyList = ["options", "placeholder", "min_values", "max_values"];

        } else if ($type == 8) {
            $dataList = [
                "channel_types" => $channel_types,
                "placeholder" => $placeholder,
                "default_values" => $this->default_values,
                "min_values" => $min_values,
                "max_values" => $max_values
            ];

            $dataKeyList = ["channel_types", "placeholder", "default_values", "min_values", "max_values"];

        } else {
            $dataList = [
                "placeholder" => $placeholder,
                "default_values" => $this->default_values,
                "min_values" => $min_values,
                "max_values" => $max_values
            ];

            $dataKeyList = ["placeholder", "default_values", "min_values", "max_values", ];
        }
        
        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $selectmenu = array_merge($selectmenu, $newData);
            }
        }

        array_push($this->selectmenus_container, $selectmenu);

    }

    public function set_selectmenus(){
        if(!isset($this->selectmenus_container)){
            $this->selectmenus_container = Null;
        }

        return $this->selectmenus_container;
    }

}

?>