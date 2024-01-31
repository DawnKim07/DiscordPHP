<?php

namespace Discord\Channel\Message\Components;

class TextInputs{

    private array $textinputs_container;

    private function styleConverter(string $style){
        $styleList = [
            "Short" => 1,
            "Paragraph" => 2
        ];

        if(isset($styleList[$style])){
            return $styleList[$style];
        } else {
            return Null;
        }
    }

    public function set(string $custom_id, int|string $style, string $label,
    ?int $min_length, ?int $max_length, ?bool $required=True, ?string $value, ?string $placeholder){
        
        if(!isset($this->textinputs_container)){
            $this->textinputs_container = [];
        }

        if(gettype($style)=="string"){
            $style = $this->styleConverter($style);
        }

        $textinput = [
            "custom_id" => $custom_id,
            "style" => $style,
            "label" => $label,
            "required" => $required
        ];

        $dataList = [
            "min_length" => $min_length,
            "max_length" => $max_length,
            "value" => $value,
            "placeholder" => $placeholder
        ];
        $dataKeyList = ["min_length", "max_length", "value", "placeholder"];
        
        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $textinput = array_merge($textinput, $newData);
            }
        }

        array_push($this->textinputs_container, $textinput);

    }

    public function set_textinputs(){
        if(!isset($this->textinputs_container)){
            $this->textinputs_container = Null;
        }

        return $this->textinputs_container;
    }

}

?>