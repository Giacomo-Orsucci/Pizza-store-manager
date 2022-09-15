<?php
    //controllo per non far accedere direttamente tramite url
    if(!$post['jsonData']){
        header("Location: home.php");
    }

    //lettura dell'array dal javascript
    $post = json_decode(file_get_contents('php://input'),true);
    $jsonData = $post['jsonData'];
    $user=$post['User'];

    //scrittura dell'array in formato json
    $file = "arrayingredienti".$user.".json";
    file_put_contents($file,$jsonData); 
    
    if(isset($post['elimina'])&&$post['elimina']){
        unlink("arrayingredienti".$user.".json");
    }
?>