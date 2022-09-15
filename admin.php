<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="funzioni.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="stile.css">

    <title>Admin</title>

</head>

<body class="text-black wrapper" style="background-color:#6E7348;" >
<?php

    define("CHECK_RICHIESTA","1"); //definizione della costante per accedere al file delle funzioni php
    require 'funzioni.php';

    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "PizzaExpress";
    $ids;

    $conn = @mysqli_connect($host, $user, $pass, $db);

    session_start();

    $username = $_SESSION['username'];
    $queryLogin = "SELECT loggato FROM utenti WHERE username='$username' ";
    $result = mysqli_query($conn,$queryLogin);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if (mysqli_connect_errno()) {
        die("Connessione fallita: " . mysqli_connect_error());
    }


    if(isset($_POST['logout'])) //logout tramite pulsante
        logoutBottone($conn, $_SESSION['username']);

    if(!isset($_SESSION['username']) || $row['loggato'] == 0) { //se l'utente non è loggato viene reindirizzato alla pagina di login
        header("Location: loginAdmin.html");
    }

    if(isset($_SESSION['ultimo_login'])){ //controllo sul tempo di inattività quando viene aggiornata la pagina
        if((time() - $_SESSION['ultimo_login']) > 600){
            logoutAuto($conn, $_SESSION['username']);
        }
        else{
            $_SESSION['ultimo_login'] = time();
        }
    }?>

    <script type="text/javascript"> 
        
        setInterval(function(){
            check_user();
        }, 2000);

        function check_user(){ //controllo continuo sul tempo di inattività
            
            jQuery.ajax({
                url: 'check_user.php',
                type: 'post',
                data: 'type = ajax',
                success: function(result){
                    if(result == "1"){
                        window.location.href='admin.php';
                    } 
                }
            });    
        }

    </script>

    <div class='container-fluid'>
        <div class='row my-4'>
            <div class='col'>
                <div class='card  card1 cardHeight roundedBord ' >
                    <form action='admin.php' method='POST' enctype="multipart/form-data">
                        <br>
                        <!-- card aggiungi pizza -->
                        <div class="container borderImg">
                            <div class=" imgUp ">
                                <div class="imagePreview roundedBord2"></div>
                                <label class="btn btn-primary roundedBord3">
                                    Upload<input type="file" class="uploadFile img" name="fileToUpload" value="fileToUpload" style="width: 0px;height: 0px;overflow: hidden;">
                                </label>
                            </div>
                        </div>
                        <div class='card-body '>
                            <b> Nome<input class="bg-input-ad2" type='text' name='nome' maxlength="35"> </b><br>
                            <b> Prezzo <input class="bg-input-ad2" style="width:70px;" type='text' name='prezzo'> €</b><br>
                            <b> Descrizione<textarea class="txtAreaAdmin bg-input-ad2" type='text' name='descrizione' maxlength="100"></textarea> </b><br>
                            <b>  &nbsp&nbsp Vegetariano &nbsp<input type='checkbox'  name='int1'>  &nbsp&nbsp Vegano &nbsp<input type='checkbox'  name='int2'> &nbsp&nbsp Celiaco &nbsp<input type='checkbox'  name='int3'></b>
                            <div>
                                <button type='submit' value='Aggiungi' class='btn btn-warning' name='a'>Aggiungi</button>
                                <button type='button' class='btn btn-success' data-toggle='modal' data-target='#mod0'>Ingredienti</button>
                            </div>
                            <div class='modal fade wrapper' id='mod0' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
                                <div class='modal-dialog modal-dialog-centered'  role='document'>
                                    <div class='modal-content modalLy'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='exampleModalLongTitle'>Ingredienti</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body' id='mod20'>
                                            <div class="row"  >
                                                <div class="col-lg-6 float-left">
                                                    <?php
                                                    $array=array();

                                                    if(file_exists('arrayingredienti'.$_SESSION['username'] .'.json')){
                                                        $jsonD=file_get_contents('arrayingredienti'.$_SESSION['username'] .'.json');
                                                        $array=json_decode($jsonD,true);
                                                    }
                                                        stampaIngredientiAggiungiDb2($conn,$array,$_SESSION['username']);
                                                    ?>
                                                </div>
                                                <div class="col-lg-6 float-right">
                                                    <?php
                                                        stampaIngredientiAggiungiDb($conn,$array,$_SESSION['username']);
                                                    ?>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class='col'>
                <!-- card gestione ingredienti -->
                <div class='card card1  cardHeight roundedBord'>
                        <div class='card-body wrapper scroll  roundedBord'>
                            <form action='admin.php' method='POST'>
                                <b> Nome<input class="bg-input-admin bg-input-ad2" type='text' name='nome' maxlength="35"> </b><br>
                                <b> Descrizione<input class="bg-input-admin bg-input-ad2" type='text' name='descrizione' maxlength="100"> </b><br>
                                <div>
                                    <button type='submit' value='Aggiungi' class='btn btn-warning' name='i'>Aggiungi</button>
                                </div>
                                <br>
                            </form>
                            <?php 
                                stampaIngredienti($conn);
                            ?>
                        </div>
                </div>
            </div>

            <?php
            $immagine;
            $nome;
            $prezzo;
            $descrizione;

            $query = "SELECT * FROM prodotto";

            $result = @mysqli_query($conn, $query);

            if (@mysqli_num_rows($result) != 0) {

                //si riprendono le pizze dal database
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $immagine = $row['immagine'];
                    $nome = $row['nome'];
                    $prezzo = $row['prezzo'];
                    $descrizione = $row['descrizione'];
                    $id = $row['id'];
                    $a = $row['vegetariano'];
                    $b = $row['vegano'];
                    $c = $row['celiaco'];

                    if ($a) $a = 'checked';  //controlli sulle chechbox spuntate
                    else $a = '';

                    if ($b) $b = 'checked';
                    else $b = '';

                    if ($c) $c = 'checked';
                    else $c = '';?>
                        <div class='col' id='<?php echo$id;?>'> 
                            <div class='card card2 roundedBord cardHeight ' >
                                <img height=300  class='card-img-top roundedBord borderImg' src= '<?php echo$immagine;?>' alt='Card image'>
                                    <div class='card-body '>
                                        <form action='admin.php'  method='POST' id='f<?php echo$id;?>'>
                                            <b> Nome <input class="bg-input-ad" type='text' value='<?php echo$nome;?>' name='nome' id='n<?php echo$id;?>' maxlength='35' > </b> <br>
                                            <b> Prezzo <input class="bg-input-ad" style="width:70px;"type='text' value='<?php echo$prezzo;?>'name='prezzo' id='p<?php echo$id;?>'> €</b><br>
                                            <b> Descrizione <textarea class="bg-input-ad txtAreaAdmin" type='text' value='<?php echo$descrizione;?>' name='descrizione' id='d<?php echo$id;?>' maxlength='100'><?php echo$descrizione;?></textarea>  </b><br>
                                            <b>  &nbsp&nbsp Vegetariano &nbsp<input type='checkbox' <?php echo$a;?> name='int1'>  &nbsp&nbsp Vegano &nbsp<input type='checkbox' <?php echo$b;?> name='int2'> &nbsp&nbsp Celiaco &nbsp<input type='checkbox' <?php echo$c;?> name='int3'></b>
                                            <div>
                                                <button type='submit' value='<?php echo$id;?>' class='btn btn-warning' name='m'>Modifica</button>
                                                <button type='submit' value='<?php echo$id;?>' class='btn btn-danger' name='e'>Elimina</button>
                                                <button type='button' class='btn btn-success' data-toggle='modal' data-target='#mod<?php echo$id;?>'>Ingredienti</button>
                                            </div>
                                            
                                        </form>
                                        <div class='modal fade wrapper' id='mod<?php echo$id;?>' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
                                            <div class='modal-dialog modal-dialog-centered' role='document'>
                                                <div class='modal-content modalLy'>
                                                    <div class='modal-header'>
                                                        <h5 class='modal-title' id='exampleModalLongTitle'>Ingredienti</h5>
                                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class='modal-body' id='mod2<?php echo$id;?>'>
                                                            <div class='row'>
                                                                <div class='col-lg-6 float-left'><?php 
                                                                stampaIngredientiProdotto($conn, $id); 
                                                            ?>
                                                                </div>
                                                                <div class='col-lg-6 float-right'><?php
                                                                stampaIngredientiModalDb($conn, $id);
                                                            ?>  </div>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <br>
                        </div><?php
                 } 
                 ?></div>
                    <form action='admin.php' method='POST' name='logoutForm'>
                        <div class="row col-6">
                            <button type='submit' class='btn btn-primary col-2' name='logout'> LOGOUT </button>
                            <div class="col-1"></div>
                            <button type='button' class='btn btn-primary col-2' data-toggle='modal' data-target='#modO'>ORDINI</button>
                        </div>
                    </form> 

                    <div class='modal fade wrapper' id='modO' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
                                            <div class='modal-dialog modal-dialog-centered' style="max-width:85%;"role='document'>
                                                <div class='modal-content modalLy'>
                                                    <div class='modal-header'>
                                                        <h5 class='modal-title' id='exampleModalLongTitle'>ORDINI &nbsp;</h5>
                                                        <button type='button' class='btn' style="color: #ffffff; padding:0; border:0;" onclick="refresh('O')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                                                                <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                                                            </svg>
                                                        </button>
                                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class='modal-body' id='mod2O' >
                                                            <div class='row'>
                                                                <div class='col-lg-6 float-left'><?php 
                                                                $array=array();

                                                                if(file_exists('arrayOrdinazioni.json')){
                                                                    $jsonD=file_get_contents('arrayOrdinazioni.json');
                                                                    $array=json_decode($jsonD,true);
                                                                }

                                                                stampaOrdiniConsegnati($conn,$array); 
                                                            ?>
                                                                </div>
                                                                <div class='col-lg-6 float-left'><?php 
                                                                stampaOrdini($conn,$array); 
                                                            ?>
                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                    <?php
            }
        
            //controllo sui bottoni cliccati per invocare le relative funzioni php

            if (isset($_POST['m']))
                modifica($_POST['m']);
            if (isset($_POST['e']))
                elimina($_POST['e'], $conn);
            if (isset($_POST['a']))
                aggiungi($_SESSION['username']);   
            if (isset($_POST['i']))
                aggiungiIngrediente($conn);
            if(isset($_POST['ie']))
                eliminaIngrediente($conn);
            if(isset($_POST['im']))
                modificaIngrediente($conn);   
            if (isset($_POST['aMod']))
                aggiungiIngredientiProdotto($conn,$_POST['aMod']);
            if (isset($_POST['eMod']))
                eliminaIngredientiProdotto($conn,$_POST['eMod']);
            ?>

        </div> <!-- chiusura del container -->
</body>
</html>