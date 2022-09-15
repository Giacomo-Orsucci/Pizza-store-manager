<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acquisto terminato</title>
</head>

<body>

    <?php

        //controllo per non far accedere direttamente tramite url
        if (!isset($_POST['Nome'])) {
            header("Location: home.php");
        }

        use function PHPSTORM_META\type;

        $nome = $_POST['Nome'];
        $cognome = $_POST['Cognome'];
        $CF = $_POST['Cf'];
        $nTelefono = $_POST['Numero'];
        $città = $_POST['Citta'];
        $via = $_POST['Via'];
        $civico = $_POST['Civico'];
        $descrizione = $_POST['Descrizione'];
        $Iden=$_POST['Iden'];
        $quantita=$_POST['quant'];


        $host = "localhost";
        $user = "root";
        $pass = "";
        $db = "PizzaExpress";
        $id;
        $conn = @mysqli_connect($host, $user, $pass, $db);

        if (mysqli_connect_errno()) {
            die("Connessione fallita: " . mysqli_connect_error());
        }

        $query = "SELECT cf FROM cliente WHERE cf='$CF' ";
        $result = @mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 0) { //se il cliente non è già stato registrato si aggiunge
            echo $nTelefono;
            $query = "INSERT INTO cliente VALUES('$CF','$nome','$cognome','$nTelefono')";
            $result = @mysqli_query($conn, $query);
        } else { //altrimenti si aggiornano tutti i campi che non siano il codice fiscale
            echo $nTelefono;
            $query = "UPDATE cliente SET nome='$nome', cognome='$cognome', cellulare='$nTelefono' WHERE cf='$CF'";
            $result = @mysqli_query($conn, $query);
        }

        $curDate = getdate();
        $curDate = $curDate['year'] . "-" . $curDate['mon'] . "-" . $curDate['mday'];

        //inserimento dell'ordine
        $query = "INSERT INTO ordine(cf,dettagliconsegna,data,via,civico,città) VALUES('$CF','$descrizione','$curDate','$via',$civico,'$città')";
        $result = @mysqli_query($conn, $query);

        //viene preso l'ultimo id inserito per popolare la tabella degli ordini
        $query = "SELECT max(id) as id FROM ordine";
        $result = @mysqli_query($conn, $query);

        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $id = intval($row['id']);

        $query = "SELECT id FROM prodotto";
        $result = @mysqli_query($conn, $query);

        for($i=0;$i<count($Iden);$i++){ //inserimento degli ordini nel DB

            $query = "INSERT INTO include VALUES($id,$Iden[$i],$quantita[$i])";
            $result2 = @mysqli_query($conn, $query);
            
        }

       
    ?>
</body>

</html>