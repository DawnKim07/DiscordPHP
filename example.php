<?php
use Discord;

# Class \Discord
$client = new \Discord;
$client->OnEvent("Ready", function($data){

    var_dump($data);

});

$client->run();

?>