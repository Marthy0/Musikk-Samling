<?php session_start();
include "ms_funksjoner.inc.php"; ?>
<!DOCTYPE html>
<html lang="no">
  <head>
    <meta charset="utf-8">
    <link rel='stylesheet' type='text/css' href='ms_stiler.css'/>
  </head>
  <body>
      
<!--    Album enheter                                                                            Album enheter   -->
    
      <?php
      if(isset($_GET['artistValg']))
      {
          $_SESSION['artistValg'] = $_GET['artistValg'];
      }
      if(isset($_GET['medieType']))
      {
          $_SESSION['medieType'] = $_GET['medieType'];
      }
      
      echo "<div id=enheterInfo>";
        echo  "<h3>".hentArtGru_kobling($_SESSION['artistValg'])."</h3>";
      echo "</div><br><hr>";
      $artistLink = hentArtistNavn($_SESSION['artistValg']);
      $albumHentet = hentAlbum($_SESSION['artistValg'], $_SESSION['medieType']);

      skrivUtEnheter($albumHentet, $artistLink);
      function skrivUtEnheter($albumHentet, $artistLink)
      {
        //print_r($albumHentet);
        // for($i=0;$i<count($albumHentet);$i++)
        foreach($albumHentet as $album)
        {
            $navnLink = $album[1];
            $typeLink = "Vinyl";
            if ($album[5] == "Kassett") $typeLink = "Cassette";
            if ($album[5] == "CD") $typeLink = "CD";
          echo "<div class='enhet'>";
          echo "<span onclick='window.top.open(\"http://discogs.com/search/?format_exact=$typeLink&artist=$artistLink&title=$navnLink\", \"discogs\")'>";
            echo "<h3 class='navn'>".$album[1]."</h3> <h3 class='tilstand'>".$album[2]."</h3></span>";
            // echo "<A href=\"http://discogs.com/search/?format_exact=$typeLink&artist=$artistLink&title=$navnLink\" target=\"discogs\">";
            $bilde = $album[3];
            if (empty($bilde)) {
              $bilde = "blank.jpg";
            }
            echo "<span onclick=\"window.top.visbilde(' ms_bildealbum_org/". $bilde . "')\">";
            echo "<img src='ms_bildealbum/$bilde' alt='medie bilde' class='bilde'></span>";

            // echo "<img src='ms_bildealbum/$bilde' alt='medie bilde' class='bilde'></A>";
            echo "<p class='andreArtister'>".andreArtister($album[0])."</p>";
            echo "<p class='beskrivelse'>".$album[4]."</p>";
          echo "</div>";
        }
      }
      ?>
      <!-- <div class='enhet'>
        <h3 class='navn'>navn</h3> <h3 class='tilstand'>tilstand</h3>
        <img src='ms_bildealbum/picture.jpg' alt='medie bilde' class='bilde'>
        <p class='beskrivelse'>beskrivelse</p>
      </div> -->


  </body>
</html>
