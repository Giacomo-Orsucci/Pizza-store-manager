<?php
    //controllo per impedire l'accesso diretto via url
    if(!($_POST['id2'])){
        header("Location: home.php");
    }

    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "PizzaExpress";

    $conn = @mysqli_connect($host, $user, $pass, $db);

    if (mysqli_connect_errno()) {
        die("Connessione fallita: " . mysqli_connect_error());
    }

    $id = $_POST['id2'];
    $nomeProdotto = $_POST['nome2'];

    //eliminazione dell'ingrediente dal prodotto
    $query = "DELETE FROM contiene WHERE nomeIngrediente = '$nomeProdotto' AND idProdotto = $id";

    $result = @mysqli_query($conn, $query);
    
?>