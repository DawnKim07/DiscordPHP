<?php

namespace Discord\Channel\Message\Components;

class Buttons{

    private array $buttons_container;

    private function styleConverter(string $style){
        $styleList = [
            "Primary" => 1,
            "Secondary" => 2,
            "Success" => 3,
            "Danger" => 4,
            "Link" => 5
        ];

        if(isset($styleList[$style])){
            return $styleList[$style];
        } else {
            return Null;
        }

    }

    public function add(int|string $style, ?string $label, ?\Discord\Emoji $emoji, ?string $custom_id,
    ?string $url, ?bool $disabled=False){

        if(!isset($this->buttons_container)){
            $this->buttons_container = [];
        }

        if(gettype($style)=="string"){
            $style = $this->styleConverter($style);
        }

        $button = [
            "type" => 2,
            "style" => $style,
            "disabled" => $disabled
        ];

        $dataList = [
            "label" => $label,
            "emoji" => $emoji->set_emoji_for_components(),
            "custom_id" => $custom_id,
            "url" => $url
        ];
        $dataKeyList = ["label", "emoji", "custom_id", "url"];
        
        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $button = array_merge($button, $newData);
            }
        }

        array_push($this->buttons_container, $button);

    }

    public function set_buttons(){
        if(!isset($this->buttons_container)){
            $this->buttons_container = Null;
        }

        return $this->buttons_container;
    }

}

?>