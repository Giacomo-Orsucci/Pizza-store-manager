

//funzione ajax per il refresh delle modal senza dover refreshare l'intera pagina
function refresh(id) {

    var id = '#mod2' + id;

    $(id).load('admin.php ' + id, id);

    //controllo sul tempo di inattività quando si refresha la modal
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

//funzione ajax per lo spostamento dei ingredienti da inclusi ad esclusi e viceversa
function ajaxMover(nomeFile, id, nome) {

    $.ajax({

        url: nomeFile, //la pagina che include lo script php
        type: "post", 
        data: {
            id2: id,
            nome2: nome
        },
        success: function (result) {
            refresh(id);
        }
    });

    refresh(id);
}

//funzione ajax di aggiunta degli ingredienti all'array degli ingredienti inclusi nel nuovo prodotto
function ajaxMover2(nome, array,user) {

    array.push(nome);

    array=JSON.stringify(array);

    var file = 'arrayAggiungiPizza.php';

    fetch(file, {
        method:'post',
        body: JSON.stringify({jsonData: array,User: user}),
        Headers:{'Content Type': 'aplication/json;charset=utf 8'}
    }).then(function () {
        refresh(0);
    }).catch(err => console.error(err));

    refresh(0);
}

//funzione ajax di eliminazione degli ingredienti dall'array di aggiunta del nuovo prodotto
function ajaxMover3( nome, array,user) {

    array.splice(array.indexOf(nome),1);
    var el=false;
    if(array.length==0)
        el=true;
    

    array=JSON.stringify(array);

    var file = 'arrayAggiungiPizza.php';

    fetch(file, {
            method:'post',
            body: JSON.stringify({jsonData: array,User: user,elimina: el}),
            Headers:{'Content Type': 'aplication/json;charset=utf 8'}
    }).then(function () {
        refresh(0);
    }).catch(err => console.error(err));
    
    refresh(0);
    
}
//funzione ajax di aggiunta degli ingredienti all'array degli ingredienti inclusi nel nuovo prodotto
function ajaxMover4(id, array) {

    array.push(id);

    array=JSON.stringify(array);

    var file = 'arrayOrdinazioni.php';

    fetch(file, {
        method:'post',
        body: JSON.stringify({jsonData: array}),
        Headers:{'Content Type': 'aplication/json;charset=utf 8'}
    }).then(function () {
        refresh("O");
    }).catch(err => console.error(err));

    refresh("O");
}

//funzione ajax di eliminazione degli ingredienti dall'array di aggiunta del nuovo prodotto
function ajaxMover5( id, array) {

    array.splice(array.indexOf(id),1);
    

    array=JSON.stringify(array);

    var file = 'arrayOrdinazioni.php';

    fetch(file, {
            method:'post',
            body: JSON.stringify({jsonData: array}),
            Headers:{'Content Type': 'aplication/json;charset=utf 8'}
    }).then(function () {
        refresh("O");
    }).catch(err => console.error(err));
    
    refresh("O");
    
}

//funzione di controllo sulla pizze selezionate al momento del passaggio alla pagina di resoconto
function controlloHome(){

    alert("E' necessario selezionare almeno una pizza per effettuare un ordine");
    window.location.href='home.php';
}

// funzione per l'upload della immagini al momento dell'aggiunta di una nuova pizza
$(".imgAdd").click(function () {
    $(this).closest(".row").find('.imgAdd').before('<div class="col-sm-2 imgUp"><div class="imagePreview"></div><label class="btn btn-primary">Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width:0px;height:0px;overflow:hidden;"></label><i class="fa fa-times del"></i></div>');
});
$(document).on("click", "i.del", function () {
    $(this).parent().remove();
});
$(function () {
    $(document).on("change", ".uploadFile", function () {
        var uploadFile = $(this);
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; //controllo sul supporto e la selezione del file da caricare

        if (/^image/.test(files[0].type)) { // solo file di immagini sono ammessi
            var reader = new FileReader(); // inizializzazione del file reader
            reader.readAsDataURL(files[0]); // lettura del file locale

            reader.onloadend = function () { // settaggio dell'anteprima dell'immagine caricata
                uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url(" + this.result + ")");
            }
        }
    });
});

//validazione ti tutte le varie form di admin
async function validazione(nome, prezzo, descrizione, a,b,c,id,funzione,urlImg,apici,backsalsh,user) {

    var validazione = true;

    if(apici){
        alert("Sono stati usati apici singoli che non sono supportati. Gli apici sono stati rimossi");
    }
    if(backsalsh){
        alert("Sono stati usati backslash che non sono supportati. I backslash sono stati rimossi");
    }
    if (nome.trim() == "") {
        alert("E' necessario inserire il nome");
        validazione = false;

    }
    if(funzione=="1")
        if(urlImg.includes("*")||urlImg.includes("|")||urlImg.includes(":")||urlImg.includes("<")||urlImg.includes(">")||urlImg.includes("?")){
            alert("il nome del file non puo includere / \\ \" : | < > ? * ");
            validazione = false;
        }
    if (prezzo == NaN) {
        alert("E' necessario inserire il prezzo");

        validazione = false;

    }
    if (descrizione.trim() == "") {
        alert("E' necessario inserire la descrizione");
        validazione = false;

    }
    if (descrizione.length > 100) {
        alert("Descrizione troppo lunga, non deve superare i 100 caratteri");
        validazione = false;

    }
    if (prezzo <= 0.009) {
        alert("Il prezzo non può essere minore di 1 centesimo");
        validazione = false;

    }
    if(urlImg!=0){
        if(urlImg=="caricareImg"){
            alert("non è stata selezionata nessuna immagine o il file selezionato non è un immagine");
            validazione = false;
        }
        if(urlImg=="big"){
            alert("L'immagine è troppo pesante");
            validazione = false;
        }
        if(urlImg=="Esiste"){
            alert("l'immagine è gia presente nel database, cambiare nome dell' immagine o eliminare la pizza che la contiene");
            validazione = false;
        }
        if(urlImg=="type"){
            alert("il tipo non è supportato. I tipi di immagine supportati sono i seguenti: PG, PNG, JPEG, GIF");
            validazione = false;
        }
        if(urlImg=="noImg"){
            alert("non è stata trovata nessuna immagine, riprovare ");
            validazione = false;
        }
        if(urlImg=="errGenNelUpload"){
            alert("errore sconosciuto nell' upload ");
            validazione = false;
        }
    }
    
    //se la validazione è andata a buon fine si richiama la pagina "validazione.php"
    if(validazione){

        var file = 'validazione.php';

        $.ajax({

            url: file, //la pagina che include lo script php
            data: {
                Nome: nome, 
                Prezzo: prezzo, 
                Descrizione: descrizione,
                A: a, 
                B: b, 
                C: c,
                Id: id,
                Funzione: funzione,
                UrlImg: urlImg
            },
            type: "POST", 
            dataType: "json",
            success: function (result) {
                window.location.href='admin.php';
            }
        });
    } 
    else if(funzione=="1"){

        var file = 'unlinkImg.php';
        $.ajax({

            url: file, //la pagina che include lo script php
            data: {
                UrlImg: urlImg,
                User: user
            },
            type: "POST", 
            success: function (result) {
                window.location.href='admin.php';
            }
        });

    }
    window.location.href='admin.php';  
}

//validazione degli ingredienti dalla pagina di amministrazione 
async function validazione2(nome, descrizione,funzione,apici,backsalsh) {

    var validazione = true;

    if(apici){
        alert("Sono stati usati apici singoli che non sono supportati. Gli apici sono stati rimossi");
    }
    if(backsalsh){
        alert("Sono stati usati backslash che non sono supportati. I backslash sono stati rimossi");
    }
    if (nome.trim() == "") {
        alert("E' necessario inserire il nome");
        validazione = false;

    }
    if (descrizione.trim() == "") {
        alert("E' necessario inserire la descrizione");
        validazione = false;

    }
    if (descrizione.length > 100) {
        alert("Descrizione troppo lunga, non deve superare i 100 caratteri");
        validazione = false;

    }

    //se la validazione è andata a buon fine si richiama la pagina "validazione.php"
    if(validazione){

        var file = 'validazione.php';

        $.ajax({

            url: file, //la pagina che include lo script php
            type: "POST", 
            data: {
                Nome: nome, 
                Descrizione: descrizione,
                Funzione: funzione
            },  
            success: function(result){
                console.log(result);
                if(result == "err"){
                    alert("l'ingrediente esiste già cambiare il nome se si desidera continuare");
                    window.location.href='admin.php';
                }
            }
            
        });
        window.location.href='admin.php';
    } 
}

//validazione dei dati inseriti dall' utente nel resoconto dell' ordine
async function validazione3() {
    var nome = document.getElementById('nome').value;
    var cognome = document.getElementById('cognome').value;
    var cf  = document.getElementById('codiceFiscale').value;
    var numero= document.getElementById('nTelefono').value;
    var via= document.getElementById('inputCity').value;
    var citta= document.getElementById('città').value;
    var civico= document.getElementById('inputZip').value;
    var descrizione=document.getElementById('descrizione').value;
    var id=[];
    var quantita=[];


    var elements = document.getElementsByClassName("pz"); //si prendono le pizze ordinate
    var elements2 = document.getElementsByClassName("pz1");
    
    for(var i=0; i<elements.length; i++) {
        id.push(elements[i].name);
        quantita.push(elements2[i].name);
    }


    nome=nome.replace("'","");
    nome=nome.replace("\\","");
    cognome=cognome.replace("'","");
    cognome=cognome.replace("\\","");
    cf=cf.replace("'","");
    cf=cf.replace("\\","");
    via=via.replace("'","");
    via=via.replace("\\","");
    descrizione=descrizione.replace("'","");
    descrizione=descrizione.replace("\\","");

    var validazione = true;

    if (nome.trim() == "") {
        alert("E' necessario inserire il nome");
        validazione = false;
    }
    if (numero.trim() == "") {
        alert("E' necessario inserire la descrizione");
        validazione = false;

    }
    if (cf.trim() == "") {
        alert("E' necessario inserire il codice fiscale");
        validazione = false;

    }
    if (cf.length <16) {
        alert("E' necessario inserire un codice fiscale corretto (lunghezza 16)");
        validazione = false;

    }
    if (via.trim() == "") {
        alert("E' necessario inserire la via di consegna");
        validazione = false;

    }
    if (civico.trim() == "") {
        alert("E' necessario inserire il numero civico di consegna");
        validazione = false;

    }
    if (citta.trim() == "") {
        alert("E' necessario selezionare la citta di consegna");
        validazione = false;

    }
    if (numero.length <10) {
        alert("E' necessario inserire un numero di cellulare valido (lunghezza 10)");
        validazione = false;

    }
    if (descrizione.length > 200) {
        alert("Descrizione troppo lunga, non deve superare i 100 caratteri");
        validazione = false;

    }

    //se la validazione è andata a buon fine si ringrazia l'utente e si manda alla pagina per la conclusione dell'ordine
    if(validazione){
        
        var file = 'acquistoTerminato.php';
        $.ajax({

            url: file, //la pagina che include lo script php
            data: {
                Nome: nome,
                Cognome: cognome, 
                Cf: cf, 
                Numero: numero,
                Via: via, 
                Citta: citta,
                Civico: civico, 
                Descrizione: descrizione,
                Iden: id,
                quant: quantita
            },
            type: "POST", 
            success: function (result) {
                window.location.href='paginaTermine.html';
            }
        });
        window.location.href='paginaTermine.html';
    }
}

//funzione di aumento della quantità della pizza selezionata dall'utente 
function selezionePiu(id) {
    var valore = document.getElementById(id).value;
    valore++;

    document.getElementById(id).value = valore;
}

//funzione di diminuzione della quantità della pizza selezionata dall'utente 
function selezioneMeno(id) {
    var valore = document.getElementById(id).value;

    valore--;

    if (valore < 0 )
        valore = 0;

    document.getElementById(id).value = valore;
}
