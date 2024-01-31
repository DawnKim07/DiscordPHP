<?php

namespace Discord\Channel\Message\Embeds;

class Provider{
    public string $name, $url;

    private array $provider;

    public function set(?string $name, ?string $url){

        $provider = [];

        $dataList = [
            "name" => $name,
            "url" => $url
        ];
        $dataKeyList = ["name", "url"];
        
        foreach($dataKeyList as $dataKey){
            if($dataList[$dataKey] != Null){
                $newData = [
                    $dataKey => $dataList[$dataKey]
                ];
                $provider = array_merge($provider, $newData);
            }
        }

        $this->provider = $provider;

    }

    public function set_provider(){
        if(!isset($this->provider)){
            $this->provider = Null;
        }

        return $this->provider;
    }

    public function get(\Discord\Channel\Message\Embeds $embeds){
        if(!isset($embeds->provider)){
            return Null;
        }

        $provider = $embeds->provider;

        $keyList = ["name", "url"];
        foreach($keyList as $key){
            if(isset($provider->$key)){
                $this->$key = $provider->$key;
            } else {
                $this->$key = Null;
            }
        }
    }
}

?>