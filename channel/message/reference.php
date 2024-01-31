<?php

namespace Discord\Channel\Message;

class Reference{
    public string $message_id, $channel_id, $guild_id;
    public bool $fail_if_not_exists;

    private array $reference;

    public function set(?string $message_id, ?string $channel_id, ?string $guild_id, ?bool $fail_if_not_exists){
        
        $reference = [];

        $dataList = [
            "message_id" => $message_id,
            "channel_id" => $channel_id,
            "guild_id" => $guild_id,
            "fail_if_not_exists" => $fail_if_not_exists
        ];
        $dataKeyList = ["message_id", "channel_id", "guild_id", "fail_if_not_exists"];

        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $reference = array_merge($reference, $newData);
            }
        }

        $this->reference = $reference;

    }

    public function set_reference(){
        if(!isset($this->reference)){
            $this->reference = Null;
        }
        
        return $this->reference;
    }
}

?>