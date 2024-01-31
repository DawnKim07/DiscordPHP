<?php

namespace Discord\Channel\Message;

class Attachments{

    private array $attachments_container_for_message;

    public function add_for_message(string $id, string $url, string $filename, ?string $description,
    ?string $content_type, ?string $proxy_url, ?bool $ephemeral){

        if(!isset($this->attachments_container_for_message)){
            $this->attachments_container_for_message = [];
        }

        $attachment = [
            "id" => $id,
            "url" => $url,
            "filename" => $filename
        ];

        $dataList = [
            "description" => $description,
            "content_type" => $content_type,
            "proxy_url" => $proxy_url,
            "ephemeral" => $ephemeral
        ];
        $dataKeyList = ["description", "content_type", "proxy_url", "ephemeral"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $attachment = array_merge($attachment, $newData);
            }
        }

        array_push($this->attachments_container_for_message, $attachment);

    }

    public function set_attachments_for_message(){
        if(!isset($this->attachments_container_for_message)){
            $this->attachments_container_for_message = Null;
        }

        return $this->attachments_container_for_message;
    }
}

?>