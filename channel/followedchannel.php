<?php

namespace Discord\Channel;

class FollowedChannel{
    public string $channel_id, $webhook_id;

    public function get(\Discord\Channel $channel){
        $followed_channel = $channel->followed_channel;

        $keyList = ["channel_id", "webhook_id"];
        foreach($keyList as $key){
            if(isset($followed_channel->$key)){
                $this->$key = $followed_channel->$key;
            } else {
                $this->$key = Null;
            }
        }
    }
}

?>