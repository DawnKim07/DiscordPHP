<?php

namespace Discord\DataFormat;

class Image{

    private string $imageData;

    public function set(string $path){
        $dataType = pathinfo($path, PATHINFO_EXTENSION);
        $rawData = file_get_contents($path);
        $base64Data = "data:image/" . $dataType . ";base64," . base64_encode($rawData);

        $this->imageData = $base64Data;
    }

    public function set_image(){
        if(isset($this->imageData)){
            return $this->imageData;
        } else {
            return Null;
        }
    }
}

?>