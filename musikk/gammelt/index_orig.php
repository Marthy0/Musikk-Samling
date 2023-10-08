<?php session_start();
include "ms_funksjoner.inc.php"; ?>
<!DOCTYPE html>
<html lang="no">
  <head>
    <meta charset="utf-8">
    <title>Musikk samling</title>
    <link rel='stylesheet' type='text/css' href='ms_stiler.css'/>
    <?php include "ms_topp.php"; ?><!--navigasjonsbaren-->
  </head>
  <body>
<!--    Artistmeny                                                                            Artistmeny      -->
    <div id="artistvalg" height="500px"><!--Artist div-->
      <table id="artistTabell"><!--artist-menyen-->
        <?php
          $artister = hentArtister();//artistene i en to-dimensjonell array

          for($i=0;$i<count($artister);$i++)//går igjennom artistene
          {
            if(iconv_substr($artister[$i][1],0,1)!=iconv_substr($artister[$i-1][1],0,1))//Sjekker om det er en ny bokstav
            {
              echo "<tr><td onclick=location.href='?artistKnapp=".iconv_substr($artister[$i][1],0,1)."'>".iconv_substr($artister[$i][1],0,1)."</A></td></tr>";//SKriver ut bokstav
            }
            echo "<tr><td align='center' onclick=location.href='?artistKnapp={$artister[$i][0]}'>{$artister[$i][1]}</A></td></tr>";//Skriver ut artist i menyen
          }
        ?>
      </table>
    </div>

<!--    Album enheter                                                                            Album enheter   -->
    <div id="enheter"><!--div med albumene-->
      <?php
      if(isset($_GET['artistKnapp']))//SJekker om artist er valgt
      {
        $_SESSION["valgtArtist"]=$_GET['artistKnapp'];//setter valgt artist til artist-session
      }
      if(!isset($_SESSION['valgtArtist']))//sjekker om artist-session ikke er definert/om artist er valgt
      {
        $_SESSION["valgtArtist"]="A";//setter artist-session til A
      }
      echo "<div id=enheterInfo>";
        echo  hentArtGru_kobling();
      echo "</div>";
      $artistLink = hentArtistNavn($_SESSION["valgtArtist"]);
      $albumHentet = hentAlbum($_SESSION["valgtArtist"]);

      skrivUtEnheter($albumHentet, $artistLink);
      function skrivUtEnheter($albumHentet, $artistLink)
      {
        //print_r($albumHentet);
        for($i=0;$i<count($albumHentet);$i++)
        {
            $navnLink = $albumHentet[$i][1];
            $typeLink = "Vinyl";
            if ($albumHentet[$i][5] == "Kassett") $typeLink = "Cassette";
            if ($albumHentet[$i][5] == "CD") $typeLink = "CD";
          echo "<div class='enhet'>";
            echo "<h3 class='navn'>".$albumHentet[$i][1]."</h3> <h3 class='tilstand'>".$albumHentet[$i][2]."</h3>";
            echo "<A href=\"http://discogs.com/search/?format_exact=$typeLink&artist=$artistLink&title=$navnLink\" target=\"discogs\">";
            $bilde = $albumHentet[$i][3];
            if (empty($bilde)) {
              $bilde = "blank.jpg";
            }
            echo "<img src='ms_bildealbum/$bilde' alt='medie bilde' class='bilde'></A>";
            echo "<p>".andreArtister($albumHentet[$i][0])."</p>";
            echo "<p class='beskrivelse'>".$albumHentet[$i][4]."</p>";
          echo "</div>";
        }
      }
      ?>
      <!-- <div class='enhet'>
        <h3 class='navn'>navn</h3> <h3 class='tilstand'>tilstand</h3>
        <img src='ms_bildealbum/picture.jpg' alt='medie bilde' class='bilde'>
        <p class='beskrivelse'>beskrivelse</p>
      </div> -->
    </div>

  </body>
</html>
