<?php

namespace Discord\Channel\Message\Embeds;

class Thumbnail{
    public string $url, $proxy_url;
    public int $height, $width;

    private array $thumbnail;

    public function set(string $url, ?string $proxy_url, ?int $height, ?int $width){

        $thumbnail = [
            "url" => $url
        ];

        $dataList = [
            "proxy_url" => $proxy_url,
            "height" => $height,
            "width" => $width
        ];
        $dataKeyList = ["proxy_url", "height", "width"];
        
        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $thumbnail = array_merge($thumbnail, $newData);
            }
        }

        $this->thumbnail = $thumbnail;

    }

    public function set_thumbnail(){
        if(!isset($this->thumbnail)){
            $this->thumbnail = Null;
        }

        return $this->thumbnail;
    }

    public function get(\Discord\Channel\Message\Embeds $embeds){
        if(!isset($embeds->thumbnail)){
            return Null;
        }

        $thumbnail = $embeds->thumbnail;
        
        $keyList = ["url", "proxy_url", "height", "width"];
        foreach($keyList as $key){
            if(isset($thumbnail->$key)){
                $this->$key = $thumbnail->$key;
            } else {
                $this->$key = Null;
            }
        }
    }
}

?>