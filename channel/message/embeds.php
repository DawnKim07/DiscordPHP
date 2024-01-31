<?php

namespace Discord\Channel\Message;

class Embeds{
    public string $title, $type, $description, $url, $timestamp;
    public int $color;
    public object $footer, $image, $thumbnail, $video, $provider, $author, $fields;

    private array $embeds_container;

    public function add(?string $title, ?string $type, ?string $description, ?string $url, ?string $timestamp,
    ?int $color, ?Embeds\Footer $footer, ?Embeds\Image $image, ?Embeds\Thumbnail $thumbnail, ?Embeds\Video $video,
    ?Embeds\Provider $provider, ?Embeds\Author $author, ?Embeds\Fields $fields){

        if(!isset($this->embeds_container)){
            $this->embeds_container = [];
        }

        $embed = [];

        $dataList = [
            "title" => $title,
            "type" => $type,
            "description" => $description,
            "url" => $url,
            "timestamp" => $timestamp,
            "color" => $color,
            "footer" => $footer->set_footer(),
            "image" => $image->set_image(),
            "thumbnail" => $thumbnail->set_thumbnail(),
            "video" => $video->set_video(),
            "provider" => $provider->set_provider(),
            "author" => $author->set_author(),
            "fields" => $fields->set_fields()
        ];
        $dataKeyList = ["title", "type", "description", "url", "timestamp", "color", "footer",
        "image", "thumbnail", "video", "provider", "author", "fields"];
        
        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $embed = array_merge($embed, $newData);
            }
        }

        array_push($this->embeds_container, $embed);

    }

    public function set_embeds(){
        if(!isset($this->embeds_container)){
            $this->embeds_container = Null;
        }

        return $this->embeds_container;
    }

    public function get(\Discord\Channel\Message $message, ?int $index){
        if(!isset($message->embeds)){
            return Null;
        }

        if($index == Null){
            return $message->embeds;
        } else {
            $embed = $message->embeds[$index];

            $keyList = ["title", "type", "description", "url", "timestamp", "color", "footer", "image",
            "thumbnail", "video", "provider", "author", "fields"];
            foreach($keyList as $key){
                if(isset($embed->$key)){
                    $this->$key = $embed->$key;
                } else {
                    $this->$key = Null;
                }
            }
        }

    }

}

?>