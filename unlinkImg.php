<?php
    $url=$_POST['UrlImg'];
    $user=$_POST['User']; //eliminazione del link dell'immagine nel caso in cui ci siano errori sull'inserimento
    unlink($url);
    unlink("arrayingredienti".$user.".json");
?>