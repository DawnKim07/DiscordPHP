<?php

namespace Discord\Channel\Message\Embeds;

class Author{
    public string $name, $url, $icon_url, $proxy_icon_url;

    private array $author;

    public function set(string $name, ?string $url, ?string $icon_url, ?string $proxy_icon_url){

        $author = [
            "name" => $name
        ];

        $dataList = [
            "url" => $url,
            "icon_url" => $icon_url,
            "proxy_icon_url" => $proxy_icon_url
        ];
        $dataKeyList = ["url", "icon_url", "proxy_icon_url"];
        
        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $author = array_merge($author, $newData);
            }
        }

        $this->author = $author;

    }

    public function set_author(){
        if(!isset($this->author)){
            $this->author = Null;
        }

        return $this->author;
    }

    public function get(\Discord\Channel\Message\Embeds $embeds){
        if(!isset($embeds->author)){
            return Null;
        }

        $author = $embeds->author;

        $keyList = ["name", "url", "icon_url", "proxy_icon_url"];
        foreach($keyList as $key){
            if(isset($author->$key)){
                $this->$key = $author->$key;
            } else {
                $this->$key = Null;
            }
        }
    }
}

?>