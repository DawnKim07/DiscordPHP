<?php

class ExtFunction{

    public function array_add($array, $field, $data){
        if(isset($data)){
            $array[$field] = $data;
        }

        return $array;
    }

    public function build_multipart_body(string $boundary, array $payload_json, array $files){
        
        $body = "--$boundary
        Content-Disposition: form-data; name=" . '"payload_json"
        Content-Type: application/json
        
        {
        ';

        while($value = current($payload_json)){
            $body .= '  "' . key($value) . '": ' . '"' . $value . '",
            ';
        }

        for($FileNumber=0; $FileNumber<count($files); $FileNumber++){
            $FilePath = $files[$FileNumber]["filepath"];
            $FileType = $files[$FileNumber]["content-type"];

            $FileContent = file_get_contents($FilePath);
            $FileName = basename($FilePath);

            $body .= "}
            --$boundary
            Content-Disposition: form-data; name=files[$FileNumber]; filename=" . '"' . "$FileName" . '"
            Content-Type: ' . "$FileType
            
            $FileContent";

            if($FileNumber == count($files) - 1){
                $body .= "
                --$boundary--";
            }
        }

        return $body;

    }

}

?>