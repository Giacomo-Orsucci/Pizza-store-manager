<?php
    //controllo per non far accedere direttamente tramite url
    if(!$post['jsonData']){
        header("Location: home.php");
    }

    //lettura dell'array dal javascript
    $post = json_decode(file_get_contents('php://input'),true);
    $jsonData = $post['jsonData'];

    //scrittura dell'array in formato json
    $file = "arrayOrdinazioni.json";
    file_put_contents($file,$jsonData); 
?>