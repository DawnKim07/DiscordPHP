<?php

namespace Discord\Channel\Message\Embeds;

class Image{
    public string $url, $proxy_url;
    public int $height, $width;

    private array $image;

    public function set(string $url, ?string $proxy_url, ?int $height, ?int $width){

        $image = [
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
                $image = array_merge($image, $newData);
            }
        }

        $this->image = $image;

    }

    public function set_image(){
        if(!isset($this->image)){
            $this->image = Null;
        }

        return $this->image;
    }

    public function get(\Discord\Channel\Message\Embeds $embeds){
        if(!isset($embeds->image)){
            return Null;
        }

        $image = $embeds->image;

        $keyList = ["url", "proxy_url", "height", "width"];
        foreach($keyList as $key){
            if(isset($image->$key)){
                $this->$key = $image->$key;
            } else {
                $this->$key = Null;
            }
        }
    }
}

?>