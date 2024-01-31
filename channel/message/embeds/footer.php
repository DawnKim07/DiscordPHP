<?php

namespace Discord\Channel\Message\Embeds;

class Footer{
    public string $text, $icon_url, $proxy_icon_url;

    private array $footer;

    public function set(string $text, ?string $icon_url, ?string $proxy_icon_url){

        $footer = [
            "text" => $text
        ];

        $dataList = [
            "icon_url" => $icon_url,
            "proxy_icon_url" => $proxy_icon_url
        ];
        $dataKeyList = ["icon_url", "proxy_icon_url"];
        
        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $footer = array_merge($footer, $newData);
            }
        }

        $this->footer = $footer;

    }

    public function set_footer(){
        if(!isset($this->footer)){
            $this->footer = Null;
        }

        return $this->footer;
    }

    public function get(\Discord\Channel\Message\Embeds $embeds){
        if(!isset($embeds->footer)){
            return Null;
        }

        $footer = $embeds->footer;

        $keyList = ["text", "icon_url", "proxy_icon_url"];
        foreach($keyList as $key){
            if(isset($footer->$key)){
                $this->$key = $footer->$key;
            } else {
                $this->$key = Null;
            }
        }
    }
}

?>