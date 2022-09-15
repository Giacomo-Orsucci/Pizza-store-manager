<?php
    //controllo per non far accedere direttamente l'utente
    if(!isset($_SESSION['username'])){
        header("Location: admin.php");
    }

    //definizione della costante per accedere al file delle funzioni php
    define("CHECK_RICHIESTA","1");
    require 'funzioni.php';

    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "PizzaExpress";
    $ids;

    $conn = @mysqli_connect($host, $user, $pass, $db);

    if (mysqli_connect_errno()) {
        die("Connessione fallita: " . mysqli_connect_error());
    }
    session_start(); //recupero della sessione utente inizializzata precedentemente

       if(isset($_SESSION['username'])){ //controllo sul tempo di inattività dell'utente

            if(time() - $_SESSION['ultimo_login'] > 600){
                echo 1;
                logoutAuto($conn, $_SESSION['username']);
            }
        }
?>