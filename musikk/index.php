<?php session_start();
$_SESSION["medieType"]="Vinyl";
include "ms_funksjoner.inc.php"; ?>
<!DOCTYPE html>
<html lang="no">
  <head>
    <meta charset="utf-8">
    <link rel="icon" href="logo.jpg" type="image/icon type">
    <title>Musikk samling</title>
    <link rel='stylesheet' type='text/css' href='ms_stiler.css'/>
    <?php include "ms_topp.php"; ?><!--navigasjonsbaren-->
  </head>
  <body>
      
    <div id="myNav" class="overlay" onclick="closeNav()">
  
        <div  class="overlay-content">
            <img id="x" src="logo.jpg" width="80%">
        </div>
    </div>
    
        <script>
    function visbilde(img) {
        document.getElementById("x").src=img;
        document.getElementById("myNav").style.width = "800px";
    }   

    function closeNav() {
        document.getElementById("myNav").style.width = "0%";
    }

    </script>
    
<!--    Artistmeny                                                                            Artistmeny      -->
    <div id="artistvalg" height="500px"><!--Artist div-->
      <table id="artistTabell"><!--artist-menyen-->
        <?php
          $artister = hentArtister();//artistene i en to-dimensjonell array
            
          for($i=0;$i<count($artister);$i++)//går igjennom artistene
          {
            // if(iconv_substr($artister[$i][1],0,1)!=iconv_substr($artister[$i-1][1],0,1))//Sjekker om det er en ny bokstav
            if(forbokstav($artister[$i][1])!=forbokstav($artister[$i-1][1]))//Sjekker om det er en ny bokstav
            {
              // echo "<tr><td onclick='document.getElementById(\"iramme\").src=\"ms_albumenheter.php?artistValg=".iconv_substr($artister[$i][1],0,1)."\"'>".iconv_substr($artister[$i][1],0,1)."</td></tr>";//SKriver ut bokstav
              echo "<tr><td onclick='document.getElementById(\"iramme\").src=\"ms_albumenheter.php?artistValg=".forbokstav($artister[$i][1])."\"'>".forbokstav($artister[$i][1])."</td></tr>";//SKriver ut bokstav
            }
            echo "<tr><td align='center' onclick='document.getElementById(\"iramme\").src=\"ms_albumenheter.php?artistValg={$artister[$i][0]}\"'>{$artister[$i][1]}</td></tr>";//Skriver ut artist i menyen
          }
        ?>
      </table>
    </div>

<!--    Album enheter                                                                       Album enheter   -->
    <div id="enheter"><!--div med albumene-->
        <iframe id="iramme" height="100%" width="100%" frameBorder="0" src="ms_albumenheter.php">
        
        </iframe>
    </div>
    
  </body>
</html>
