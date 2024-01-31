<?php

namespace Discord\Channel\Message\Embeds;

class Video{
    public string $url, $proxy_url;
    public int $height, $width;

    private array $video;

    public function set(?string $url, ?string $proxy_url, ?int $height, ?int $width){

        $video = [];

        $dataList = [
            "url" => $url,
            "proxy_url" => $proxy_url,
            "height" => $height,
            "width" => $width
        ];
        $dataKeyList = ["url", "proxy_url", "height", "width"];
        
        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $video = array_merge($video, $newData);
            }
        }

        $this->video = $video;

    }

    public function set_video(){
        if(!isset($this->video)){
            $this->video = Null;
        }

        return $this->video;
    }

    public function get(\Discord\Channel\Message\Embeds $embeds){
        if(!isset($embeds->video)){
            return Null;
        }

        $video = $embeds->video;

        $keyList = ["url", "proxy_url", "height", "width"];
        foreach($keyList as $key){
            if(isset($video->$key)){
                $this->$key = $video->$key;
            } else {
                $this->$key = Null;
            }
        }
    }
}

?>