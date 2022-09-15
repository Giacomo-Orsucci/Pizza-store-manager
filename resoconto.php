<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="funzioni.js"> </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="stile.css">
    <title>Resoconto</title>

</head>

<body class="bg-light text-black" style="background-color:#3D89D9!important;">

    <?php
        
        $pizzeOrdinate = array();
        $nome = array();
        $prezzo = array();

        $host = "localhost";
        $user = "root";
        $pass = "";
        $db = "PizzaExpress";
        $id;
        $conn = @mysqli_connect($host, $user, $pass, $db);

        if (mysqli_connect_errno()) {
            die("Connessione fallita: " . mysqli_connect_error());
        }

        $query = "SELECT id,nome,prezzo FROM prodotto"; 

        $result = @mysqli_query($conn, $query);

        //prendiamo le pizze e la quantità selezionati nella pagina home e le info relative alle pizze dal database
        if (@mysqli_num_rows($result) != 0) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

                $id = $row['id'];
                $Q = 'Q' . $id;
                if ((int)@$_GET[$Q] > 0) {
                    $pizzeOrdinate[$id] = $_GET[$Q];
                    $nome[$id] = $row['nome'];
                    $prezzo[$id] = $row['prezzo'];
                }
            }
        }

        if(empty($pizzeOrdinate)){ //se non è stata selezionata alcuna pizza si rimanda alla home tramite javascript
            ?>
            <script> controlloHome() </script>
            <?php
        }

        ?>
        <!-- form per l'inserimento dei dati dell'utente per concludere l'ordine -->
        <div class='container-fluid'>

            <div class='row'>

                <div class='col-lg-6 formResoconto'>

                    <form>
                        <h2 class="horizontal-"> INSERISCI I DATI DELL'ORDINE </h2>

                        <div class="form-group">
                            <label><b>Nome</b></label> 
                            <input class="form-control" type="text" placeholder="Inserisci il nome"  id="nome" maxlength="30" >

                        </div>
                        <div class="form-group">
                            <label><b>Cognome</b></label> 
                            <input class="form-control" type="text" placeholder="Inserisci il cognome" id="cognome" maxlength="30">

                        </div>
                        <div class="form-group">
                            <label><b>Codice Fiscale</b></label> 
                            <input class="form-control" type="text" placeholder="Inserisci il Codice Fiscale" id="codiceFiscale" maxlength="16" minlength="16">

                        </div>
                        <div class="form-group">
                            <label><b>Numero di telefono</b></label> 
                            <input class="form-control" type="text"  onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Inserisci il numero di telefono" id="nTelefono" maxlength="10" minlength="10">

                        </div>

                        <div class='row'>

                            <div class="form-group col-md-4">
                                <label><b>Città</b></label>
                                <select class="form-control" id="città">
                                    <option selected>Montecatini Terme</option>
                                    <option>Monsummano</option>
                                    <option>Pieve a Nievole</option>
                                    <option>Pistoia</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputCity"><b>Via</b></label>
                                <input type="text" class="form-control" id="inputCity"  maxlength="38">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputZip"><b>Civico</b></label>
                                <input type="number" class="form-control" id="inputZip" min="1" >
                            </div>
                            &nbsp&nbsp&nbsp&nbsp<small class="form-text text-muted" style=" font-size: 17px; color: white!important;">I tuoi dati sensibili verranno utilizzati solamente allo scopo di fornirti il nostro servizio. Ci teniamo alla tua privacy!</small>

                            <br><br>
                        </div>
                        <div class="form-group">
                            <label><b>Dettagli Ordine</b></label>
                            <textarea class="form-control" rows="3" placeholder="Inserisci qui qualche dettaglio sull'ordine (tipo non suonare il campanello o la data e l'orario della consegna, altrimenti noi consegneremo il prima possibile)" id="descrizione" maxlength="200"></textarea>
                        </div>
                        <br><br>
                        <button type="button" class="btn bg-btn-hm" onclick="validazione3()">Conferma ordine</button>
                    </form>

                </div>
                <!-- tabella di resoconto dell'ordine con le pizze selezionate e la quantità relativa -->
                <div class='col-lg-6 carrelloResoconto'>
                    <h2> RESOCONTO ORDINE </h2>
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Pizza</th>
                                <th scope="col">Quantità</th>
                                <th scope="col">Prezzo parziale</th>
                            </tr>
                        </thead>
                        <?php
                        $prezzoParziale = 0;
                        $totale = 0;
                        foreach ($pizzeOrdinate as $id => $value) {
                            $prezzoParziale = $prezzo[$id] * $value;
                            echo "
                            <thead class='thead-light'>
                                <tr>
                                    <th scope='col' > $nome[$id]</th>
                                    <input  class='pz' type='hidden'  name='$id'>
                                    <input  class='pz1' type='hidden'  name='$value'>
                                    <th scope='col' name='Q1$id'> $value</th>
                                    <th scope='col'>$value X $prezzo[$id]€ : $prezzoParziale €</th>
                                </tr>
                            </thead>";
                            $totale = $totale + $prezzoParziale;
                        }
                        ?>
                    </table>
                    <table class="table col-lg-4 float-right">
                        <thead class="thead-dark ">
                            <tr>
                                <th scope="col">Totale</th>
                                <th scope="col" class="thead-light"><?php echo $totale ?> €</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>