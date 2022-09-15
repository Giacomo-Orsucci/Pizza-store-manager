<?php
        //controllo per non far accedere direttamente tramite url
        if(!($_POST['id2'])){
            header("Location: home.php");
        }

        $host = "localhost";
        $user = "root";
        $pass = "";
        $db = "PizzaExpress";
        $ids;

        $conn = @mysqli_connect($host, $user, $pass, $db);

        if (mysqli_connect_errno()) {
            die("Connessione fallita: " . mysqli_connect_error());
        }

        $id = $_POST['id2'];

        $nomeProdotto = $_POST['nome2'];

        //inserimento nella tabella contiene contenente le pizze ed i relativi ingredienti
        $query = "INSERT INTO contiene VALUES ($id,'$nomeProdotto')";

        $result = @mysqli_query($conn, $query);
?>