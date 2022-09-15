<?php
    //controllo per non far accedere direttamente l'utente tramite url
    if(!isset($_POST['Funzione'])){
        header("Location: home.php");
    }

    //controllo per non far accedere direttamente l'utente al file delle funzioni php
    define("CHECK_RICHIESTA","1");
    require 'funzioni.php';

    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "PizzaExpress";

    $conn = @mysqli_connect($host, $user, $pass, $db);


    if (mysqli_connect_errno()) {
        die("Connessione fallita: " . mysqli_connect_error());
    }

    $funzione=@$_POST['Funzione'];

    if($funzione=="0"){ //modifica prodotto
        $Nome=@$_POST['Nome'];
        $Prezzo=@$_POST['Prezzo'];
        $Descrizione=@$_POST['Descrizione'];
        $A=@$_POST['A'];
        $B=@$_POST['B'];
        $C=@$_POST['C'];
        $Id=@$_POST['Id'];
        $update = "UPDATE prodotto SET nome = '$Nome', prezzo = $Prezzo, descrizione = '$Descrizione', vegetariano=$A, vegano=$B, celiaco=$C where id=$Id";

        $result = @mysqli_query($conn, $update);
    }
    else if($funzione=="1"){ //aggiungi prodotto
        $Nome=@$_POST['Nome'];
        $Prezzo=@$_POST['Prezzo'];
        $Descrizione=@$_POST['Descrizione'];
        $A=@$_POST['A'];
        $B=@$_POST['B'];
        $C=@$_POST['C'];
        $Id=@$_POST['Id'];
        $immagine=@$_POST['UrlImg'];

        $upload = "INSERT INTO prodotto(nome,prezzo,descrizione,vegetariano,celiaco,vegano,immagine) VALUES ('$Nome', $Prezzo, '$Descrizione', $A,$C,$B,'$immagine')";
        $result = @mysqli_query($conn, $upload);

        //aggiunta del prodotto con gli ingredienti relativi nella tabella "include"
        if (file_exists('arrayingredienti'.$_SESSION['username'] .'.json')) { 
            $query = "SELECT LAST_INSERT_ID()";
            $result = @mysqli_query($conn, $query);

            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $id = (int)$row['LAST_INSERT_ID()'];

            $jsonD = file_get_contents('arrayingredienti'.$_SESSION['username'] .'.json');
            $array = json_decode($jsonD, true);

            foreach ($array as $ingrediente) {
                $query = "INSERT INTO contiene VALUES ($id,'$ingrediente')";
                $result = @mysqli_query($conn, $query);
            }
            unlink('arrayingredienti'.$_SESSION['username'] .'.json');
        }
        
    }   
    else if($funzione=="2"){ //aggiunta dei nuovi ingredienti
        $Nome=@$_POST['Nome'];
        $Descrizione=@$_POST['Descrizione'];

        $controlloNome = @mysqli_query($conn, "SELECT nome FROM ingrediente WHERE nome like '$Nome' ");

        $row = mysqli_fetch_array($controlloNome, MYSQLI_ASSOC);

        if ($row['nome'] == $Nome) 
            echo "err"; //echo per gestire poi tramite AJAX l'errore perché l'ingrediente è già presente nel database
        else 
            $aggiungiIngrediente = @mysqli_query($conn, "INSERT INTO ingrediente VALUES ('$Nome', '$Descrizione' )");
        
    }
    else if($funzione=="3"){ //modifica degli ingredienti modificati dalla pagina di amministrazione nel database
        $Nome=@$_POST['Nome'];
        $Descrizione=@$_POST['Descrizione'];
        $modificaDescrizione = @mysqli_query($conn, "UPDATE ingrediente SET descrizione = '$Descrizione' WHERE nome = '$Nome'");
    }
