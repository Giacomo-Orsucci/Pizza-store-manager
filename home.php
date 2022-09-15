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
    <title>Homepage</title>
    <style>
        .roundedBord {
            border-radius: 30px;
        }

        .borderImg {
            padding: 5px;
        }

        input[type=number] { /*disabilita le freccettine dell'input su firefox*/
        -moz-appearance: textfield;
        }

    </style>
</head>

<body class=" text-black wrapper" style="background-color: #3D89D9!important;">

    <?php
        //definizione della costante per la gestione degli accessi al file delle funzioni php
        define("CHECK_RICHIESTA", "1");
        require 'funzioni.php';

        $host = "localhost";
        $user = "root";
        $pass = "";
        $db = "PizzaExpress";

        $id;
        $immagine;
        $nome;
        $prezzo;
        $descrizione;
        $vegetariano;
        $vegano;
        $celiaco;


        $conn = @mysqli_connect($host, $user, $pass, $db);

        if (mysqli_connect_errno()) {
            die("Connessione fallita: " . mysqli_connect_error());
        }

    ?>
    <!-- effetto di parallasse dell'immagine di copertina -->
    <div id="header" class="parallax" style="display: flex; justify-content: center; align-items: center;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1700.5 277.03">
            <defs>
                <style>
                    .cls-1 {
                        font-size: 227px;
                        fill: #fff;
                        stroke: #000;
                        stroke-miterlimit: 10;
                        stroke-width: 3px;
                        font-family: Georgia-Bold, Georgia;
                        font-weight: 700;
                    }
                </style>
            </defs>
            <g id="Livello_2" data-name="Livello 2">
                <g id="Livello_1-2" data-name="Livello 1">
                    <text class="cls-1" transform="translate(1.5 193.64)">
                        Pizza Express
                    </text>
                </g>
            </g>
        </svg>
    </div>

    <br>

    <form action='resoconto.php' method='GET' name='form1'>

        <div class='container-fluid'>
            
            <?php

                $query = "SELECT * FROM prodotto"; //se il filtro rimane vuoto

                if (isset($_GET['applica'])) { //se il filtro viene applicato

                    $filtro = $_GET['filtro'];
                    $checkbox = @$_GET['checkbox'];
                    $stringaIngredienti = " nomeIngrediente = ";


                    if (!empty($checkbox)) { // se il filtro ingredienti è stato attivato

                        for ($i = 0; $i < count($checkbox); $i++) {

                            if ($i == count($checkbox) - 1)
                                $stringaIngredienti = $stringaIngredienti . '"' . $checkbox[$i] . '" GROUP BY id';
                            else
                                $stringaIngredienti = $stringaIngredienti . '"' . $checkbox[$i] . '"' . " OR nomeIngrediente =";
                        }
                    }

                if (empty($checkbox) && $filtro != 'Seleziona...') { // se è stato selezionato solamente il filtro celiaco etc

                    if ($filtro == 'Vegano') {
                        $query = "SELECT * FROM prodotto WHERE vegano = 1";
                    }

                    if ($filtro == 'Vegetariano') {
                        $query = "SELECT * FROM prodotto WHERE vegetariano = 1";
                    }

                    if ($filtro == 'Celiaco') {
                        $query = "SELECT * FROM prodotto WHERE celiaco = 1";
                    }

                    if ($filtro == 'Celiaco e vegano') {
                        $query = "SELECT * FROM prodotto WHERE celiaco = 1 AND vegano = 1";
                    }

                    if ($filtro == 'Celiaco e vegetariano') {
                        $query = "SELECT * FROM prodotto WHERE celiaco = 1 AND vegetariano = 1";
                    }
                } else if ($filtro == 'Seleziona...' && !empty($checkbox)) { //se è stato selezionato solo il filtro sugli ingredienti
                    $query = "SELECT * FROM prodotto inner join contiene on id = idProdotto WHERE  $stringaIngredienti";
                } else { // se è stato selezionato sia il filtro su celiaco etc che il filtro sugli ingredienti

                    if ($filtro == 'Vegano') {
                        $query = "SELECT * FROM prodotto inner join contiene on id = idProdotto WHERE vegano = 1 AND $stringaIngredienti";
                    }

                    if ($filtro == 'Vegetariano') {
                        $query = "SELECT * FROM prodotto inner join contiene on id = idProdotto WHERE vegetariano = 1 AND $stringaIngredienti";
                    }

                    if ($filtro == 'Celiaco') {
                        $query = "SELECT * FROM prodotto  inner join contiene on id = idProdotto WHERE celiaco = 1 AND $stringaIngredienti";
                    }

                    if ($filtro == 'Celiaco e vegano') {
                        $query = "SELECT * FROM prodotto  inner join contiene on id = idProdotto WHERE celiaco = 1 AND vegano = 1 AND $stringaIngredienti";
                    }

                    if ($filtro == 'Celiaco e vegetariano') {
                        $query = "SELECT * FROM prodotto  inner join contiene on id = idProdotto WHERE celiaco = 1 AND vegetariano = 1 AND $stringaIngredienti";
                    }
                }
            }

            $result = @mysqli_query($conn, $query);

            if(@mysqli_num_rows($result) == 0){
                    echo "
                    <script>
                        alert('il filtro non ha risultati');
                            window.location.href='home.php';
                    </script>";
                }

            //si prendono le pizze dal database
            if (@mysqli_num_rows($result) != 0) {

                echo "<div class='row my-4'>";
                $i = 0;

                
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

                    $id = $row['id'];
                    $immagine = $row['immagine'];
                    $nome = $row['nome'];
                    $prezzo = $row['prezzo'];
                    $descrizione = $row['descrizione'];
                    $vegetariano = $row['vegetariano'];
                    $vegano = $row['vegano'];
                    $celiaco = $row['celiaco'];


                    $i++;

                    //controlli sulle checkbox spuntate
                    if ($vegetariano) $vegetariano = 'checked';
                    else $vegetariano = '';
                    if ($celiaco) $celiaco = 'checked';
                    else $celiaco = '';
                    if ($vegano) $vegano = 'checked';
                    else $vegano = ''; ?>

                    <!-- card delle pizze -->
                    <div class='col'>
                        <div class='card2 card roundedBord cardHeight ' style='height: 597px ; width: 445.7655px;'>
                            <img height=300 width=411 class='card-img-top roundedBord borderImg' style="object-fit: cover;" src='<?php echo $immagine; ?>' alt='Card image'>
                            <div class='card-body' style=" width:445.75px; height:297px;">
                                <h2><?php echo $nome; ?></h2>
                                <p> Prezzo:<?php echo $prezzo; ?> €</p>
                                <div>
                                    <textarea class="txtAreaHome " cols="51" rows="3"><?php echo $descrizione; ?></textarea>
                                    <b> &nbsp&nbsp Vegetariano &nbsp<input type='checkbox' name='int' <?php echo $vegetariano; ?> disabled>
                                        &nbsp&nbsp Vegano &nbsp<input type='checkbox' name='int' <?php echo $vegano; ?> disabled> &nbsp&nbsp Celiaco &nbsp<input type='checkbox' name='int' <?php echo $celiaco; ?> disabled></b>
                                    <div class="container">
                                    <div class='row'>
                                        
                                        <div class='col'>
                                            <button type='button' class='btn btn-success' style="padding:2px; border:1px;" onclick='selezionePiu(<?php echo $id; ?>)'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-arrow-up-circle' viewBox='0 0 16 16'>
                                                    <path fill-rule='evenodd' d='M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V11.5z' />
                                                </svg>
                                            </button>
                                            <br>
                                            <button type='button' class='btn btn-danger'style="padding:2px; border:1px;" onclick='selezioneMeno(<?php echo $id; ?>)'>
                                                <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-arrow-down-circle-fill' viewBox='0 0 16 16'>
                                                    <path d='M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z' />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class='col'>
                                            <br>
                                            <input id=<?php echo $id; ?> type='number' style='font-size: x-large; width: 100px!important; background-color: #D9BD32!important; border:0;' readonly value='0' name='Q<?php echo $id; ?>'>
                                        </div>
                                        <div class='  col'>
                                            <br>
                                            <button type='button' class='btn btn-success' data-toggle='modal' data-target='#mod<?php echo $id; ?>'>Ingredienti</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <div class='modal fade' id='mod<?php echo $id; ?>' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
                                <div class='modal-dialog modal-dialog-centered' role='document'>
                                    <div class='modal-content modalLy'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='exampleModalLongTitle'>Ingredienti</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body'>
                                            <?php
                                            stampaIngredientiProdottoHome($conn, $id);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                <?php
                } ?>
        </div>
        <!-- chiude il primo tag div row-->
    <?php
    } ?>

    <!-- footer con i bottoni -->
    <div class="footer1" id="vanesco">
        <br>
        <div id="resoconto">
            <input type='submit' class='btn bg-btn-hm float-right' value='PAGAMENTO' name='resoconto'>
        </div>

    </form>
    <form action='loginAdmin.html' style="float: left;">
        <input type='submit' class='btn btn-success' value='login admin' name='login admin'>
    </form>
    <form action='home.php' method='GET'>
        <div>
            <button type='button' class='btn bg-btn-hm' data-toggle='modal' data-target='#modFiltro' name='Filtrobtn'>FILTRO INGREDIENTI</button>
        </div>
        </div>
        <div class='modal fade' id='modFiltro' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
            <div class='modal-dialog modal-dialog-centered' role='document'>
                <div class='modal-content modalLy'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='exampleModalLongTitle'>Seleziona gli ingredienti</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <div class="row" id='modBod'>
                            <div class="col-lg-12">
                                <?php stampaFiltroIngredienti($conn); ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 0px;">
                        <input type="submit" class="btn bg-btn-hm" value="Applica" name="applica">
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
    <script>
        var header = document.getElementById('header')
        var footer = document.getElementById('vanesco');

        //effetto di fade dell'immagine di copertina e del footer
        function fadeOutOnScroll(element, elemnt2) {
            if (!element) {
                return;
            }

            var distanceToTop = window.pageYOffset + element.getBoundingClientRect().top;
            var elementHeight = element.offsetHeight;
            var scrollTop = document.documentElement.scrollTop;

            var opacity = 1;

            if (scrollTop > distanceToTop) {
                opacity = 1 - (scrollTop - distanceToTop) / elementHeight;
            }

            if (opacity >= 0) {
                element.style.opacity = opacity;
            }

            if (opacity < 0.5524728588661038) {
                elemnt2.className = 'footer';
                elemnt2.style.opacity = -((1 - (scrollTop - distanceToTop)/621) * 7);
            }
        }

        function scrollHandler() {
            fadeOutOnScroll(header, footer);
        }

        window.addEventListener('scroll', scrollHandler);
    </script>
    <br>
    <br>
    <br>
    <br>
</body>
</html>