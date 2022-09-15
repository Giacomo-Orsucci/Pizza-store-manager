<?php

    //controllo per non far accedere direttamente l'utente tramite url
    if (!defined("CHECK_RICHIESTA")) {
        header("Location: home.php");
    }

    //funzione di logout per inattività
    function logoutAuto($conn, $username)
    {
        $query = "UPDATE utenti SET loggato = 0 WHERE username='$username'";
        $result = mysqli_query($conn, $query);

        $_SESSION = array();
        unset($_SESSION);

        session_destroy();
    }
    //funzione che stampa le ordinazioni non anocra consegnate
    function stampaOrdini($conn, $ids)
    {
        $query = "SELECT * FROM ordine join cliente on ordine.cf = cliente.cf";
        
        if (!empty($ids)) {

            if ($ids[0] !== null) {
                $query = $query . " where";
                foreach ($ids as $id) {
                    $query = $query . " id <> '$id' and";
                }
                $query = substr($query, 0, -1);
                $query = substr($query, 0, -1);
                $query = substr($query, 0, -1);
                
            }

            $result = @mysqli_query($conn, $query);
            $i = 0;

            $arrayString = implode("','", $ids);
            if ($arrayString != '')
                $arrayString = ",['" . $arrayString . "']";
            else
                $arrayString = ",[]";
        } else {
            $result = @mysqli_query($conn, $query);
            $arrayString = ",[]";
        }
        
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

            $id= $row['id'];
            $nome = $row['nome'];
            $cellulare = $row['cellulare'];
            $dettaglio = $row['dettagliconsegna'];
            $via = $row['via'];
            $citta = $row['città'];
            $civico= $row['civico'];
            
            ?>
                <div class='bg-dark text-light roundedBord1'>
                        <form class='paddingIngredienti' action='admin.php' method='POST'>
                            &nbsp<b>  &nbsp <input class='txt-ing-dark2'style="width:200px;" type='text'value='NOME: <?php echo $nome; ?>' name='nome' readonly> &nbsp
                            <b>&nbsp<input class='txt-ing-dark2'style="width:200px;" type='text'value='CELLULARE: <?php echo $cellulare; ?>' name='descrizione' maxlength='100' readonly></b>&nbsp
                            &nbsp &nbsp<input class='txt-ing-dark2'style="width:200px;" type='text'value='VIA: <?php echo $via; ?>' name='descrizione' maxlength='100' readonly>&nbsp
                             &nbsp <input class='txt-ing-dark2'style="width:200px;" type='text' value='CITTA: <?php echo $citta; ?>' name='nome' readonly>
                            &nbsp  &nbsp <input class='txt-ing-dark2'  style="width:200px;" type='text' value='CIVICO: <?php echo $civico; ?>' name='nome' readonly>
                            &nbsp <input class='txt-ing-dark2' style="width:100%;" type='text-box'value='DETTAGLI:  <?php echo $dettaglio; ?>' name='nome' readonly></b><br>
                            <button type='button'  class='btn btn-warning roundedBord' onclick="ajaxMover4('<?php echo $id; ?>'<?php echo $arrayString; ?>)">-</button>
                            <button type='button' data-toggle="collapse"  class='btn btn-warning roundedBord' data-target="#collap<?php echo $id;?>" >Pizze Ordinate</button>    
                        </form>
                        <div id="collap<?php echo $id;?>" class="collapse"><?php
                            $query2 = "SELECT * FROM include join prodotto on include.IDprodotto = prodotto.id where idordine=$id";
                            $result2 = @mysqli_query($conn, $query2);
                            $prezzoTot=0;
                            while ($row = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                                $nomePizza=$row['nome'];
                                $quantita=$row['quantita'];
                                $prezzo=$row['prezzo'];
                                $prezzoTot=$prezzoTot+($prezzo*$quantita);
                                echo "&nbsp $quantita X $nomePizza || ";

                            }
                            echo "<br> &nbsp TOTALE: $prezzoTot €";
                        ?>
                        </div>
                </div><br>
            <?php
            
        }
    }

    //funzione che stampa le ordinazioni consegnate
    function stampaOrdiniConsegnati($conn, $ids)
    {
        $query = "SELECT * FROM ordine join cliente on ordine.cf = cliente.cf";

        if (!empty($ids)) {
            if ($ids[0] !== null) {
                $query = $query . " where";
                foreach ($ids as $id) {
                    $query = $query . " id like '$id' or";
                }
                $query = substr($query, 0, -1);
                $query = substr($query, 0, -1);
            }

            $result = @mysqli_query($conn, $query);
            $i = 0;
            $arrayString = implode("','", $ids);
            if ($arrayString != '')
                $arrayString = ",['" . $arrayString . "']";
            else
                $arrayString = ",'-'";
            
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $id= $row['id'];
                $nome = $row['nome'];
                $cellulare = $row['cellulare'];
                $dettaglio = $row['dettagliconsegna'];
                $via = $row['via'];
                $citta = $row['città'];
                
            $civico= $row['civico'];
            
            ?>
                <div class='bg-success text-light roundedBord1'>
                <form class='paddingIngredienti' action='admin.php' method='POST'>
                            &nbsp<b>  &nbsp <input class='txt-ing-green2'style="width:200px;" type='text'value='NOME: <?php echo $nome; ?>' name='nome' readonly> &nbsp
                            <b>&nbsp<input class='txt-ing-green2'style="width:200px;" type='text'value='CELLULARE: <?php echo $cellulare; ?>' name='descrizione' maxlength='100' readonly></b>&nbsp
                            &nbsp &nbsp<input class='txt-ing-green2'style="width:200px;" type='text'value='VIA: <?php echo $via; ?>' name='descrizione' maxlength='100' readonly>&nbsp
                             &nbsp <input class='txt-ing-green2'style="width:200px;" type='text' value='CITTA: <?php echo $citta; ?>' name='nome' readonly>
                            &nbsp  &nbsp <input class='txt-ing-green2'  style="width:200px;" type='text' value='CIVICO: <?php echo $civico; ?>' name='nome' readonly>
                            &nbsp <input class='txt-ing-green2' style="width:100%;" type='text'value='DETTAGLI:  <?php echo $dettaglio; ?>' name='nome' readonly></b><br>
                            <button type='button'  class='btn btn-danger roundedBord' onclick="ajaxMover4('<?php echo $id; ?>'<?php echo $arrayString; ?>)">+</button>
                            <button type='button' data-toggle="collapse"  class='btn btn-danger roundedBord' data-target="#collap<?php echo $id;?>" >Pizze Ordinate</button>    
                        </form>
                            <div id="collap<?php echo $id;?>" class="collapse"><?php
                            $query2 = "SELECT * FROM include join prodotto on include.IDprodotto = prodotto.id where idordine=$id";
                            $result2 = @mysqli_query($conn, $query2);
                            $prezzoTot=0;
                            while ($row = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                                $nomePizza=$row['nome'];
                                $quantita=$row['quantita'];
                                $prezzo=$row['prezzo'];
                                $prezzoTot=$prezzoTot+($prezzo*$quantita);
                                echo "&nbsp $quantita X $nomePizza|";

                            }
                            echo "<br> &nbsp TOTALE: $prezzoTot €";
                        ?>
                        </div>
                    </div><br><?php
                
            

                
            }
        }
    }


    //funzione di logout tramite il bottone nella pagina di amministrazione
    function logoutBottone($conn, $username)
    {
        $query = "UPDATE utenti SET loggato = 0 WHERE username='$username'";
        $result = mysqli_query($conn, $query);

        $_SESSION = array();
        unset($_SESSION);

        session_destroy();
        echo "Disconnessione riuscita, arrivederci!";
        echo "<script>
            window.location.href='admin.php';
            </script>";
    }

    //funzione per stampare gli ingredienti nelle card di gestione degli ingredienti
    function stampaIngredienti($conn)
    {
        $query = "SELECT * FROM ingrediente";

        $result = @mysqli_query($conn, $query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $nome = $row['nome'];
            $descrizione = $row['descrizione'];
            echo "<div class='bg-dark text-light roundedBord1'>
                        <form class='paddingIngredienti' action='admin.php' method='POST'>
                            &nbsp<b> Nome&nbsp<input class='txt-ing-dark' type='text'value='$nome' name='nome' readonly> &nbsp</b> <button type='submit' value='elimina' class='btn btn-danger roundedBord' name='ie'>-</button><br>
                            &nbsp<b> Descrizione&nbsp<input class='txt-ing-dark' type='text'value='$descrizione' name='descrizione' maxlength='100'>&nbsp</b> <button type='submit' value='Modifica' class='btn btn-warning roundedBord' name='im'>+</button>
                        </form>
                    </div><br>";
        }
    }

    //funzione che stampa le opzioni di filtraggio dei prodotti nella home 
    function stampaFiltroIngredienti($conn)
    {
        echo "<select id='inputState' class='form-control' name='filtro'>
                    <option selected>Seleziona...</option>
                    <option>Vegano</option>
                    <option>Vegetariano</option>
                    <option>Celiaco</option>
                    <option>Celiaco e vegano</option>
                    <option>Celiaco e vegetariano</option>
                </select> <br>";

        $query = "SELECT * FROM ingrediente";

        $result = @mysqli_query($conn, $query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $nome = $row['nome'];
            echo "<div class='form-check form-check-inline'>
                        <input class='form-check-input' type='checkbox' value='$nome' name='checkbox[]'>
                        <label class='form-check-label' for='inlineCheckbox1'> $nome </label>
                    </div>";
        }
    }

    //funzione che stampa gli ingredienti dei prodotti nella modal della pagina home
    function stampaIngredientiProdottoHome($conn, $id)
    {
        $query = "SELECT nomeIngrediente, descrizione FROM contiene INNER JOIN ingrediente on nomeIngrediente = nome where idProdotto = $id";

        $result = @mysqli_query($conn, $query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $nome = $row['nomeIngrediente'];
            $descrizione = $row['descrizione'];
            echo "<div class='bg-success text-light roundedBord1'>
                        <form class='paddingIngredienti' action='admin.php' method='POST'>
                            <b> <input class='txt-ing-green2' style='width:100%' type='text'value='NOME: $nome' name='nome' disabled> </b> <br> 
                            <b> <input class='txt-ing-green2' style='width:100%' type='text'value='Descrizione: $descrizione' name='descrizione' disabled></b> 
                        </form>
                    </div><br>";
        }
    }

    //funzione che stampa gli ingredienti presenti nei prodotti nella modal della pagina di amministrazione
    function stampaIngredientiProdotto($conn, $id)
    {
        $query = "SELECT nomeIngrediente, descrizione FROM contiene INNER JOIN ingrediente on nomeIngrediente = nome where idProdotto = $id";

        $result = @mysqli_query($conn, $query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $nome = $row['nomeIngrediente'];
            $descrizione = $row['descrizione']; ?>
            <div class='bg-success text-light roundedBord1'>
                &nbsp<b> Nome&nbsp<input class="txt-ing-green" type='text' value='<?php echo $nome; ?>' name='nome' readonly> &nbsp</b> <br>
                &nbsp<b> Descrizione&nbsp<input class="txt-ing-green" type='text' value='<?php echo $descrizione; ?>' name='descrizione' readonly>&nbsp</b> <button type='button' value='<?php echo $id; ?>' class='btn btn-danger roundedBord' onclick="ajaxMover('eMod.php', <?php echo $id; ?>, '<?php echo $nome; ?>')">-</button>

            </div><br>
        <?php
        }
    }

    //funzione che stampa gli ingredienti non aggiunti al prodotto nella modal della pagina di admin
    function stampaIngredientiModalDb($conn, $id)
    {
        $query = "SELECT nome, descrizione FROM ingrediente WHERE nome <> ALL(SELECT nomeIngrediente FROM contiene WHERE idProdotto = $id)";

        $result = @mysqli_query($conn, $query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $nome = $row['nome'];
            $descrizione = $row['descrizione'];            ?>
            <div class='bg-dark text-light roundedBord1'>
                &nbsp<b> Nome&nbsp<input class="txt-ing-dark" type='text' value='<?php echo $nome; ?>' name='nome' readonly> &nbsp</b> <br>
                &nbsp<b> Descrizione&nbsp<input class="txt-ing-dark" type='text' value='<?php echo $descrizione; ?>' name='descrizione' readonly>&nbsp</b> <button type='button' value='<?php echo $id; ?>' class='btn btn-warning roundedBord' onclick="ajaxMover(' aMod.php',<?php echo $id; ?>,'<?php echo $nome; ?>')">+</button>

            </div><br>
        <?php
        }
    }

    //funzione che stampa gli ingredienti non usati ed aggiungibili ad un nuovo prodotto nell'apposita modal
    function stampaIngredientiAggiungiDb($conn, $ids,$user)
    {
        $query = "SELECT nome, descrizione FROM ingrediente";

        if (!empty($ids)) {

            if ($ids[0] !== null) {
                $query = $query . " where";
                foreach ($ids as $nome) {
                    $query = $query . " nome <> '$nome' and";
                }
                $query = substr($query, 0, -1);
                $query = substr($query, 0, -1);
                $query = substr($query, 0, -1);
            }

            $result = @mysqli_query($conn, $query);
            $i = 0;

            $arrayString = implode("','", $ids);
            if ($arrayString != '')
                $arrayString = ",['" . $arrayString . "']";
            else
                $arrayString = ",[]";
        } else {
            $result = @mysqli_query($conn, $query);
            $arrayString = ",[]";
            }

        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

            $nome = $row['nome'];
            $descrizione = $row['descrizione']; ?>

            <div class='bg-dark text-light roundedBord1'>
                &nbsp<b> Nome&nbsp<input class="txt-ing-dark" type='text' value='<?php echo $nome; ?>' readonly> &nbsp</b> <br>
                &nbsp<b> Descrizione&nbsp<input class="txt-ing-dark" type='text' value='<?php echo $descrizione; ?>' readonly>&nbsp</b>
                <button type='button' class='btn btn-secondary roundedBord' onclick="ajaxMover2('<?php echo $nome; ?>'<?php echo $arrayString; ?>,'<?php echo $user; ?>')">+</button>

            </div><br>

            <?php
        }
    }

    //funzione che stampa gli ingredienti aggiunti al nuovo prodotto
    function stampaIngredientiAggiungiDb2($conn, $ids,$user)
    {
        $query = "SELECT nome, descrizione FROM ingrediente ";

        if (!empty($ids)) {
            if ($ids[0] !== null) {
                $query = $query . " where";
                foreach ($ids as $nome) {
                    $query = $query . " nome like '$nome' or";
                }
                $query = substr($query, 0, -1);
                $query = substr($query, 0, -1);
            }

            $result = @mysqli_query($conn, $query);
            $i = 0;
            $arrayString = implode("','", $ids);
            if ($arrayString != '')
                $arrayString = ",['" . $arrayString . "']";
            else
                $arrayString = ",'-'";
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $nome = $row['nome'];
                $descrizione = $row['descrizione'];
            ?>
                <div class='bg-success text-light roundedBord1'>
                    &nbsp<b> Nome&nbsp<input class="txt-ing-green" type='text' value='<?php echo $nome; ?>' readonly> &nbsp</b> <br>
                    &nbsp<b> Descrizione&nbsp<input class="txt-ing-green" type='text' value='<?php echo $descrizione; ?>' readonly>&nbsp</b>
                    <button type='button' value='<?php echo $ids[$i]; ?>' class='btn btn-danger roundedBord' onclick="ajaxMover3('<?php echo $nome; ?>'<?php echo $arrayString; ?>,'<?php echo $user; ?>')">-</button>

                </div><br>
        <?php

                $i++;
            }
        }
    }

    //funzione che aggiunge nuovi ingredienti al DB dopo averli aggiunti dalla pagina di amministrazione
    function aggiungiIngrediente($conn)
    {
        $nome = $_POST['nome'];
        $descrizione = $_POST['descrizione'];
        $apici=false;
        $backslash=false;

        if(strpos($nome,"'")){
            $nome=str_replace("'","",$nome);
            $apici=true;
        } 
        if(strpos($descrizione,"'")){
            $descrizione=str_replace("'","",$descrizione);
            $apici=true;
        } 
        if(strpos($nome,"\\")){
            $nome=str_replace("\\","",$nome);
            $backslash=true;
        } 
        if(strpos($descrizione,"\\")){
            $descrizione=str_replace("\\","",$descrizione);
            $backslash=true;
        } 

        ?>
        <script>
            validazione2('<?php echo $nome; ?>', '<?php echo $descrizione; ?>', 2,'<?php echo $apici; ?>','<?php echo $backslash; ?>')
        </script>
    <?php
    }

    //funzione che elimina gli ingredienti eliminati dalla card di gestione degli ingredienti dalla pagina di amministrazione
    function eliminaIngrediente($conn)
    {
        $nome = $_POST['nome'];
        $eliminaNome = @mysqli_query($conn, "DELETE FROM ingrediente WHERE nome = '$nome'");
        $eliminaNome = @mysqli_query($conn, "DELETE FROM contiene WHERE nomeIngrediente = '$nome'");
        echo "<script>
            window.location.href='admin.php';
            </script>";
    }

    //funzione di modifica degli ingredienti dalla pagina di amministrazione
    function modificaIngrediente($conn)
    {
        $nome = $_POST['nome'];
        $descrizione = $_POST['descrizione'];

        $apici=false;
        $backslash=false;

        if(strpos($nome,"'")){
            $nome=str_replace("'","",$nome);
            $apici=true;
        } 
        if(strpos($descrizione,"'")){
            $descrizione=str_replace("'","",$descrizione);
            $apici=true;
        } 
        if(strpos($nome,"\\")){
            $nome=str_replace("\\","",$nome);
            $backslash=true;
        } 
        if(strpos($descrizione,"\\")){
            $descrizione=str_replace("\\","",$descrizione);
            $backslash=true;
        } 
    ?>
        <script>
            validazione2('<?php echo $nome; ?>', '<?php echo $descrizione; ?>', 3,'<?php echo $apici; ?>','<?php echo $backslash; ?>')
        </script>
    <?php

    }

    //funzione di eliminazione dei prodotti dalla pagina di amministrazione
    function elimina($idElimina, $conn)
    {
        $delete = "SELECT immagine FROM prodotto WHERE id=$idElimina";
        $result = @mysqli_query($conn, $delete);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        unlink($row['immagine']);
        $delete = "DELETE FROM prodotto WHERE id=$idElimina";

        $result = @mysqli_query($conn, $delete);
        echo "<script>
            window.location.href='admin.php';
            </script>";
    }

    //funzione di modifica dei prodotti dalla pagina di amministrazione
    function modifica($idModifica)
    {
        $nome = @$_POST['nome'];
        $prezzo = @$_POST['prezzo'];
        $descrizione = @$_POST['descrizione'];
        $a = @$_POST['int1'];
        $b = @$_POST['int2'];
        $c = @$_POST['int3'];

        if ($a == "on") $a = 1;
        else $a = 0;

        if ($b == "on") $b = 1;
        else $b = 0;

        if ($c == "on") $c = 1;
        else $c = 0;

        $apici=false;
        $backslash=false;

        if(strpos($nome,"'")){
            $nome=str_replace("'","",$nome);
            $apici=true;
        } 
        if(strpos($descrizione,"'")){
            $descrizione=str_replace("'","",$descrizione);
            $apici=true;
        } 
        if(strpos($nome,"\\")){
            $nome=str_replace("\\","",$nome);
            $backslash=true;
        } 
        if(strpos($descrizione,"\\")){
            $descrizione=str_replace("\\","",$descrizione);
            $backslash=true;
        } 

        if (is_null($nome)) $nome = " ";
        if (is_null($prezzo)||$prezzo==0) $prezzo = -69;
        if (is_null($descrizione)) $descrizione = " ";

    ?>
        <script>
            validazione('<?php echo $nome; ?>', <?php echo $prezzo; ?>, '<?php echo $descrizione; ?>', <?php echo $a; ?>, <?php echo $b; ?>, <?php echo $c; ?>, <?php echo $idModifica; ?>, 0, 0,'<?php echo $apici; ?>','<?php echo $backslash; ?>',0)
        </script>
    <?php
    }

    //funzione di aggiunta dei prodotti dalla pagina di amministrazione
    function aggiungi($user)
    {
        $nome = @$_POST['nome'];
        $prezzo = @$_POST['prezzo'];
        $descrizione = @$_POST['descrizione'];
        $a = @$_POST['int1'];
        $b = @$_POST['int2'];
        $c = @$_POST['int3'];

        if ($a == "on") $a = 1;
        else $a = 0;

        if ($b == "on") $b = 1;
        else $b = 0;

        if ($c == "on") $c = 1;
        else $c = 0;

        $immagine = uploadImage();

        $apici=false;
        $backslash=false;

        if(strpos($nome,"'")){
            $nome=str_replace("'","",$nome);
            $apici=true;
        } 
        if(strpos($descrizione,"'")){
            $descrizione=str_replace("'","",$descrizione);
            $apici=true;
        } 
        if(strpos($immagine,"'")){
            $immagine=str_replace("'","",$immagine);
            $apici=true;
        } 
        if(strpos($nome,"\\")){
            $nome=str_replace("\\","",$nome);
            $backslash=true;
        } 
        if(strpos($descrizione,"\\")){
            $descrizione=str_replace("\\","",$descrizione);
            $backslash=true;
        } 
        if(strpos($immagine,"\\")){
            $immagine=str_replace("\\","",$immagine);
            $backslash=true;
        }

        if (is_null($nome)) $nome = " ";
        if (is_null($prezzo)||$prezzo==0) $prezzo = -69;
        if (is_null($descrizione)) $descrizione = " ";

    ?>
        <script>
            validazione('<?php echo $nome; ?>', <?php echo $prezzo; ?>, '<?php echo $descrizione; ?>', <?php echo $a; ?>, <?php echo $b; ?>, <?php echo $c; ?>, 0, 1, '<?php echo $immagine; ?>','<?php echo $apici; ?>','<?php echo $backslash; ?>','<?php echo $user; ?>')
        </script>
    <?php


    }

    //funzione di upload delle immagini al momento dell'aggiunta di un prodotto dalla pagina di amministrazione
    function uploadImage()
    {

        $target_dir = "Immagini/";
        $target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
        list($width, $height) = getimagesize($_FILES["fileToUpload"]["tmp_name"]);


        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        // Controlla se il file è un'immagine
        if (isset($_POST["a"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                return "caricareImg";
                $uploadOk = 0;
            }
        }
        // Controlla se l'immagine è già stata caricata sul server
        if (file_exists($target_file)) {
            return "Esiste";
            $uploadOk = 0;
        }
        // Controlla le dimensioni dell'immagine
        if ($_FILES["fileToUpload"]["size"] > 2000000) {
            return "Big";
            $uploadOk = 0;
        }
        // Controllo sul formato delle immagini
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            return "type";
            $uploadOk = 0;
        }
        // Controllo sulla riuscita del caricamento dell'immagine
        if ($uploadOk == 0) {
            return "noImg";
            // Se è tutto ok carica l'immagine
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                
                return $target_file;
            } else {
                return "errGenNelUpload";
            }
        }
    }
?>