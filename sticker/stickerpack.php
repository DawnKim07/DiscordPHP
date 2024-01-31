<?php

namespace Discord\Sticker;

use Psr;
use React;

require "../vendor/autoload.php";

class Pack{

    public string $id, $name, $sku_id, $cover_sticker_id, $description, $banner_asset_id;
    public array $stickers;

    public function get_sticker_packs(){
        $loader = new \Discord\ConfigLoad();
        $loader->load();
        $token = $loader->token;

        $browser = new React\Http\Browser();
        $url = "https://discord.com/api/v10/sticker-packs";
        $header = [
            "Authorization" => "Bot" . " " . $token 
        ];

        $browser->get($url, $header)->then(function(Psr\Http\Message\ResponseInterface $response){
            $data = json_decode($response->getBody());
            $keyList =["id", "name", "sku_id", "cover_sticker_id", "description", "banner_asset_id",
            "stickers"];
            for($i=0; $i<count($keyList); $i++){
                if(isset($data->$keyList[$i])){
                    $this->$keyList[$i] = $data->$keyList[$i];
                }
            }
        });
    }

}

?>