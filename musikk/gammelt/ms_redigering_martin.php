<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Rediger</title>
    <link rel='stylesheet' type='text/css' href='../ms_stiler.css'/>
    <?php include "../ms_topp.php";
    include "../ms_funksjoner.inc.php";
    include "ms_rediger_funksjoner.inc.php"; ?>
  </head>
  <body>

    <!-- Oppdater artist -->
    <h2>Oppdater artist</h2>
    <h3>Velg Artist</h3>
    <form>
    <?php
        $artister = hentArtister();
        echo "<form action='ms_redigering.php' method='get'>";
            echo "<select name='artValgOpp'>";
            for($i=0;$i<count($artister);$i++)
            {
                echo "<option style='text-align:center' value='".$artister[$i][0]."'>".$artister[$i][1]."</option>";
            }
            echo "</select>";
            echo "<input type='submit' value='Hent artist info' name='hentArt_opp'>";
        echo "</form><br>";
        echo $artistOppValg;
        if(isset($_GET['hentArt_opp']))
        {
            $_SESSION["artistOpp_id"]=$_GET['artValgOpp'];
            $artistInfo=hentValgtArtistOpp();
            echo "<form action='ms_redigering.php' method='get'>";
                echo "<h4>Navn:</h4> <input type='text' name='artOpp_navn' value='$artistInfo[0]'>";
                echo "<h4>Instrument:</h4> <input type='text' name='artOpp_instrument' value='$artistInfo[1]'>";
                echo "<h4>Gruppe:</h4>";
                echo "<select name='artOpp_gruppe'>";
                    if($artistInfo[2]==0)
                    {
                        echo "<option value='0' selected='selected'>Enkelt artist</option>";
                        echo "<option value='1'>Gruppe</option>";
                    }
                    else
                    {
                        echo "<option value='0'>Enkelt artist</option>";
                        echo "<option value='1' selected='selected'>Gruppe</option>";
                    }
                echo "</select><br><br>";
                echo "<input type='submit' value='Oppdater artist' name='artistOpp'>";
            echo "</form><br>";
        }
    ?>
    
    <!-- Oppdater album -->
    <h2>Oppdater album</h2>
    <h3>Velg Album</h3>
    <form>
    <?php
        $album = hentAlbumOpp();
        echo "<form action='ms_redigering.php' method='get'>";
            echo "<select name='albValgOpp'>";
            for($i=0;$i<count($album);$i++)
            {
                echo "<option style='text-align:center' value='".$album[$i][0]."'>".$album[$i][1]."</option>";
            }
            echo "</select>";
            echo "<input type='submit' value='Hent album info' name='hentAlb_opp'>";
        echo "</form><br>";
        echo $artistOppValg;
        if(isset($_GET['hentAlb_opp']))
        {
            $_SESSION["albumOpp_id"]=$_GET['albValgOpp'];
            $albumInfo=hentValgtAlbumOpp();
            echo "<form action='ms_redigering.php' method='get'>";
                echo "<h4>Navn:</h4> <input type='text' name='albOpp_navn' value='$albumInfo[0]'>";
                echo "<h4>Tilstand:</h4> <input type='text' name='albOpp_tilstand' value='$albumInfo[1]'>";
                echo "<h4>Type:</h4>";
                    echo "<select name='albOpp_type'>";
                    $medieType = array("LP", "EP", "Singel", "Kassett", "CD", "Annet");
                    for($i=0; $i<count($medieType);$i++)
                    {
                        if($medieType[$i]==$albumInfo[2])
                        {
                            echo "<option value='$medieType[$i]' selected='selected'>$medieType[$i]</option>";
                        }
                        else
                        {
                            echo "<option value='$medieType[$i]'>$medieType[$i]</option>";
                        }
                    }
                    echo "</select>";
                echo "<h4>Bilde-adresse:</h4> <input type='text' name='albOpp_bildeAdr' value='$albumInfo[3]'>";
                echo "<h4>Beskrivelse:</h4> <input type='text' name='albOpp_beskrivelse' value='$albumInfo[4]'><br><br>";
                echo "<input type='submit' value='Oppdater album' name='albumOpp'>";
            echo "</form><br>";
        }
    ?>
    
  </body>
</html>
