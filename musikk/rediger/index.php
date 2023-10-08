<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Rediger</title>
    <link rel="icon" href="../logo.jpg" type="image/icon type">
    <link rel='stylesheet' type='text/css' href='../ms_stiler.css'/>
    <?php include "../ms_topp.php";
    include "ms_db_inc.php"; ?>

<script>

function jpgExt(filnavn) {
    if (!filnavn.toUpperCase().endsWith(".JPG")) filnavn = filnavn + ".JPG";
    return filnavn;
}
var kommando = "ms_kommando.php"
function httpKall(url, elementId, funk) {

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (elementId) {
                   document.getElementById(elementId).innerHTML = this.responseText;
                } else {
                   alert(this.responseText)
                }
                if (funk) funk()
            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
}

function lastOpp(artist_id) {


        var xmlhttp = new XMLHttpRequest();
        var file_data = document.getElementById("nytt_album_upload_" + artist_id)["files"][0];   
        var form_data = new FormData();                  
        form_data.append('file', file_data);
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                   alert(this.responseText)
            }
        };
        xmlhttp.open("POST", "upload.php", true);
        xmlhttp.send(form_data);
}


function visAlbum(artist_id, tvingVisning) {
    var albumKnapp = document.getElementById("vis_album_" + artist_id)
    if (artist_id != 9999) var sammensetnKnapp = document.getElementById("vis_sammensetninger_" + artist_id)

    if (tvingVisning || albumKnapp.innerHTML.substring(0,3) == "Vis") {
        albumKnapp.innerHTML = albumKnapp.innerHTML.replace("Vis", "Lukk")
        if (artist_id != 9999) sammensetnKnapp.innerHTML = sammensetnKnapp.innerHTML.replace("Lukk", "Vis")
        httpKall(kommando + "?action=getArtist&artist=" + artist_id, "album_" + artist_id)
    } else {
        albumKnapp.innerHTML = albumKnapp.innerHTML.replace("Lukk", "Vis")
        lukkAlbum(artist_id)
    }
}

function lukkAlbum(artist_id) {
    document.getElementById("album_" + artist_id).innerHTML = ""
}

function nyttAlbum(artist_id) {
    document.getElementById("nytt_album_" + artist_id).innerHTML = "<br><input type='text' id='nytt_album_navn_" + artist_id + "' placeholder='Navn'>" +
    "<input type='text' size='10' id='nytt_album_tilstand_" + artist_id + "' placeholder='Tilstand'>" +
    "<input type='text' id='nytt_album_bilde_" + artist_id + "' placeholder='Bilde'>" +
    
    "<form action='upload.php' method='post' target='xxx' enctype='multipart/form-data' style='display: inline;' onsubmit='var " +
    "filnavn = jpgExt(prompt(\"Filnavn\", document.getElementById(\"nytt_album_bilde_" + artist_id + "\").value));document.getElementById(\"nytt_album_bilde_" + artist_id + "\").value=filnavn; " +
    "this.querySelector(\"#nyfilnavn_" + artist_id + "\").value=filnavn;'>" +
    "<input type='text' name='filnavn' id='nyfilnavn_" + artist_id + "' hidden>" +
    "<input type='file' name='image' id='nytt_album_upload_" + artist_id + "' >" +
    // "<input type='button' onclick='this.hidden=\"true\";this.parentElement.querySelector(\"input[type=file]\").hidden=false;this.parentElement.querySelector(\"input[type=submit]\").hidden=false' value='Last'>" +
    "<input type='submit' id='nytt_album_submit_" + artist_id + "' value='Last opp' name='submit' >" +
    "</form>" +
    
    "<input type='text' id='nytt_album_beskr_" + artist_id + "' placeholder='Beskrivelse'>" +
    "<select id='nytt_album_type_" + artist_id + "'>" +
    "<option value='LP'>LP</option>" +
    "<option value='EP'>EP</option>" +
    "<option value='Singel'>Singel</option>" +
    "<option value='Kassett'>Kassett</option>" +
    "<option value='CD'>CD</option>" +
    "<option value='Annet'>Annet</option>" +
    "</select>" +
    "<button onclick='lagreNyttAlbum(" + artist_id +")'>Lagre</button>" +
    // "<form action='upload.php' method='post' target='xxx' enctype='multipart/form-data' style='display: inline;'>" +
    // "<input type='file' name='image' id='nytt_album_upload_" + artist_id + "'><input type='text' name='artist_id' value='" + artist_id + "' hidden> <input type='submit' value='Last opp' name='submit'>" +
    // "</form>" + 
    "<p>"
}

function nyArtist() {
    document.getElementById("ny_artist").innerHTML = "<input type='text' id='ny_artist_navn' placeholder='Navn'>" +
    "<input type='text' id='ny_artist_instrument' placeholder='Instrument'>" +
    "<select id='ny_artist_gruppe'>" +
    "<option value='0'>Enkelt Artist</option>" +
    "<option value='1'>Gruppe</option>" +
    "</select>" +
    "<button onclick='lagreNyArtist()'>Lagre</button>"
}

function lagreArtist(id, navn, instrument, gruppe) {
    httpKall(kommando + "?action=lagreArtist&artist_id=" + id + "&navn=" + navn + "&instrument=" + instrument + "&gruppe=" + gruppe, null);
}

function lagreNyArtist(navn, instrument, gruppe) {
    var navn = document.getElementById("ny_artist_navn").value,
    instrument = document.getElementById("ny_artist_instrument").value,
    gruppe = document.getElementById("ny_artist_gruppe").value
    httpKall(kommando + "?action=lagreNyArtist&navn=" + navn + "&instrument=" + instrument + "&gruppe=" + gruppe, null, function() {location.reload()});
}

function slettArtist(artist_id) {
    var artist_navn = document.getElementById("artist_navn_" + artist_id).value
    if (confirm("Vil du slette '" + artist_navn + "' ?"))
    httpKall(kommando + "?action=slettArtist&artist_id=" + artist_id + "&artist_navn=" + artist_navn, null, function() {location.reload()});
}

function visSammensetninger(artist_id, gruppe, tving) {
    var albumKnapp = document.getElementById("vis_album_" + artist_id)
    var sammensetnKnapp = document.getElementById("vis_sammensetninger_" + artist_id)
    if (tving || sammensetnKnapp.innerHTML.substring(0,3) == "Vis") {
       sammensetnKnapp.innerHTML = sammensetnKnapp.innerHTML.replace("Vis", "Lukk")
       albumKnapp.innerHTML = albumKnapp.innerHTML.replace("Lukk", "Vis")
       httpKall(kommando + "?action=visSammensetninger&artist=" + artist_id + "&gruppe=" + gruppe, "album_" + artist_id)
    } else {
        sammensetnKnapp.innerHTML = sammensetnKnapp.innerHTML.replace("Lukk", "Vis")
        lukkAlbum(artist_id)
    }
}

function fjernArtistKobling(id1, id2, navn2, gruppe) {
    var navn1 = document.getElementById("artist_navn_" + id1).value
    if (gruppe) {
        artist_id = id2; artist_navn = navn2; gruppe_id = id1; gruppe_navn = navn1;
    } else {
        artist_id = id1; artist_navn = navn1; gruppe_id = id2; gruppe_navn = navn2;
    }
    if (confirm("Vil du fjerne '" + artist_navn + "' fra gruppa '" + gruppe_navn + "' ?"))
    httpKall(kommando + "?action=fjernSammensetning&artist_id=" + artist_id + "&artist_navn=" + artist_navn +"&gruppe_id=" + gruppe_id + "&gruppe_navn=" + gruppe_navn, null, function() {visSammensetninger(id1, gruppe, true)});
}

function lagSammensetning(artist_id, gruppe) {
    httpKall(kommando + "?action=lagSammensetning&artist_id=" + artist_id + "&gruppe=" + gruppe, "sammensetning_" + artist_id);
}

function lagreSammensetning(id1, id2, navn2, gruppe) {
    var navn1 = document.getElementById("artist_navn_" + id1).value
    if (gruppe) {
        artist_id = id2; artist_navn = navn2; gruppe_id = id1; gruppe_navn = navn1;
    } else {
        artist_id = id1; artist_navn = navn1; gruppe_id = id2; gruppe_navn = navn2;
    }

    if (confirm("Vil du legge artist '" + artist_navn + "' inn i gruppa '" + gruppe_navn + "' ?"))
    httpKall(kommando + "?action=lagreSammensetning&artist_id=" + artist_id + "&artist_navn=" + artist_navn +"&gruppe_id=" + gruppe_id + "&gruppe_navn=" + gruppe_navn, null, function() {visSammensetninger(id1, gruppe, true)});
}

function lagreAlbum(artist_id, id, navn, tilstand, bilde, beskr, type) {
    httpKall(kommando + "?action=lagreAlbum&album_id=" + id + "&navn=" + navn + "&tilstand=" + tilstand + "&bilde=" + bilde + "&beskr=" + beskr + "&type=" + type, null, function() {visAlbum(artist_id, true)});
}

function lagreNyttAlbum(artist_id, navn, tilstand, bilde, beskr, type) {
    var navn = document.getElementById("nytt_album_navn_" + artist_id).value,
    tilstand = document.getElementById("nytt_album_tilstand_" + artist_id).value,
    bilde = document.getElementById("nytt_album_bilde_" + artist_id).value,
    beskr = document.getElementById("nytt_album_beskr_" + artist_id).value,
    type = document.getElementById("nytt_album_type_" + artist_id).value,
    artistNavn = document.getElementById("artist_navn_" + artist_id).value

    httpKall(kommando + "?action=lagreNyttAlbum&artist_id=" + artist_id + "&artist_navn=" + artistNavn + "&navn=" + navn + "&tilstand=" + tilstand + "&bilde=" + bilde + "&beskr=" + beskr + "&type=" + type, null, function() {visAlbum(artist_id, true)});
}


function fjernKobling(artist_id, album_id, album_navn) {
    var artistNavn = document.getElementById("artist_navn_" + artist_id).value
    if (confirm("Vil du fjerne '" + album_navn + "' fra artist '" + artistNavn + "' ?"))
    httpKall(kommando + "?action=fjernKobling&artist_id=" + artist_id + "&artist_navn=" + artistNavn +"&album_id=" + album_id + "&album_navn=" + album_navn, null, function() {visAlbum(artist_id, true)});
}

function slettAlbum(artist_id, album_id, album_navn) {
    if (confirm("Vil du slette '" + album_navn + "' ?"))
    httpKall(kommando + "?action=slettAlbum&album_id=" + album_id + "&album_navn=" + album_navn, null, function() {visAlbum(artist_id, true)});
}
    
function kobleAlbum(artist_id, album_id, album_navn) {
    httpKall(kommando + "?action=kobleAlbum&artist_id=" + artist_id + "&album_id=" + album_id + "&album_navn=" + album_navn, "koble_" + album_id);
}

function lagreKobling(artist_id, album_id, album_navn, gjeldende) {
    var artistNavn = document.getElementById("artist_navn_" + artist_id).value
    if (confirm("Vil du koble '" + album_navn + "' til artist '" + artistNavn + "' ?"))
    httpKall(kommando + "?action=lagreKobling&artist_id=" + artist_id + "&artist_navn=" + artistNavn +"&album_id=" + album_id + "&album_navn=" + album_navn, null, function() {visAlbum(gjeldende, true)});
}

</script>
</head>
  <body><iframe name="xxx" ></iframe>

    <!-- Oppdater artist -->
    <h2>Oppdater musikk database</h2>
    <h3>Velg Artist</h3>
    <?php
    echo "<button onclick='nyArtist()'>Ny artist</button>";
    echo "<span id='ny_artist'></span><br>";
        $artister = hentAlleArtister();
        
        foreach ($artister as $artist) {
            $id = $artist['id'];
            $navn = $artist['navn'];
            $instr = $artist['instrument'];
            $gruppe = $artist['gruppe'];
            echo "<input type='text' id='artist_navn_$id' value='$navn' onkeyup='document.getElementById(\"artist_lagre_$id\").hidden=false'>";
            echo "<input type='text' id='artist_instrument_$id' value='$instr' placeholder='Instrument' onkeyup='document.getElementById(\"artist_lagre_$id\").hidden=false'>";
            echo "<select id='artist_gruppe_$id' onchange='document.getElementById(\"artist_lagre_$id\").hidden=false'>";
            echo "<option value='0'";
            if (!$gruppe) echo " selected";
            echo ">Enkelt artist</option>";
            echo "<option value='1'";
            if ($gruppe) echo " selected";
            echo ">Gruppe</option>";
            echo "</select>";
            
            echo "<button onclick='slettArtist({$id})'>Slett</button>";
            $sammensetning = $gruppe ? "artister" : "grupper";
            echo "<button id='vis_sammensetninger_$id' style='width:100px;' onclick='visSammensetninger($id, $gruppe)'>Vis $sammensetning</button>";
            echo "<button id='vis_album_$id' onclick='visAlbum({$id})'>Vis album</button>";
            
            echo "<button id='artist_lagre_$id' hidden='true' onclick='lagreArtist($id, 
                  document.getElementById(\"artist_navn_$id\").value,
                  document.getElementById(\"artist_instrument_$id\").value,
                  document.getElementById(\"artist_gruppe_$id\").value
                  );document.getElementById(\"artist_lagre_$id\").hidden=true'>Lagre</button>";
            
            echo "<span id='album_{$id}'></span><br>";
        }
        echo "<button id='vis_album_9999' onclick='visAlbum(9999)'>Vis album uten artist</button>";
        echo "<span id='album_9999'></span><p>";


    ?>
    
  </body>
</html>
