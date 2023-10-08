<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Registrering</title>
    <link rel='stylesheet' type='text/css' href='../ms_stiler.css'/>
    <?php include "../ms_topp.php";
    include "../ms_funksjoner.inc.php";
    include "ms_rediger_funksjoner.inc.php"; ?>
  </head>
  <body>

    <!-- Artist -->
    <h2>Registrer artist</h2>
    <form action="ms_registrering.php" method="get">
        <h4>Navn:</h4> <input type="text" name="artReg_navn" placeholder="Navn">
        <h4>Instrument:</h4> <input type="text" name="artReg_instrument" placeholder="Tilstand">
        <h4>Gruppe:</h4>
        <select name="artReg_gruppe">
          <option value="0">Enkelt artist</option>
          <option value="1">Gruppe</option>
        </select><br><br>
        <input type='submit' value='Registrer artist' name="artistReg">
    </form><br>

    <!-- Album -->
    <h2>Registrer album</h2>
    <form action="ms_registrering.php" method="get">
        <h4>Navn:</h4> <input type="text" name="albReg_navn" placeholder="Navn">
        <h4>Tilstand:</h4> <input type="text" name="albReg_tilstand" placeholder="Tilstand">
        <h4>Beskrivelse:</h4> <input type="text" name="albReg_beskrivelse" placeholder="Beskrivelse">
        <h4>Bilde_adresse:</h4> <input type="text" name="albReg_bilde" placeholder="bildeadresse">
        <h4>Type:</h4> <select name="albReg_type">
          <option value="LP">LP</option>
          <option value="EP">EP</option>
          <option value="Singel">Singel</option>
          <option value="Kassett">Kassett</option>
          <option value="CD">CD</option>
          <option value="Annet">Annet</option>
        </select><br><br>
        <input type='submit' value='Registrer album' name="albumReg"><br><br>
    </form><br>

    <!-- Artist_album -->
    <h2>Lag artist_album kobling</h2>
    <form action="ms_registrering.php" method="get">
      <?php
        echo "<h4>Album navn:</h4>";
          $albumHentetNavnId = hentAlbumNavnId();
          echo "<select name='albKoblingReg'>";
          for($i=count($albumHentetNavnId)-1;$i>=0;$i--)
          {
            echo "<option style='text-align:center' value='".$albumHentetNavnId[$i][0]."'>".$albumHentetNavnId[$i][1]."</option>";
          }
          echo "</select>";
        echo "<h4>Artist navn:</h4>";
          $artister = hentArtister();
          echo "<select name='artKoblingReg'>";
          for($i=0;$i<count($artister);$i++)
          {
            echo "<option style='text-align:center' value='".$artister[$i][0]."'>".$artister[$i][1]."</option>";
          }
          echo "</select><br><br>";
        ?>
        <input type='submit' value='Lag album-kobling' name="artist_albumReg">
    </form><br>

    <!-- Artist_sammensetning -->
    <h2>Velg band til artister</h2>
    <form action="ms_registrering.php" method="get">
      <?php
        echo "<h4>Artist navn:</h4>";
          $artister = hentArtisterNavnGru();
          echo "<select name='artKoblingReg'>";
          for($i=0;$i<count($artister);$i++)
          {
            if($artister[$i][2]==0)
            {
              echo "<option style='text-align:center' value='".$artister[$i][0]."'>".$artister[$i][1]."</option>";
            }
          }
          echo "</select>";

          echo "<h4>Gruppe navn:</h4>";
            $artister = hentArtisterNavnGru();
            echo "<select name='gruKoblingReg'>";
            for($i=0;$i<count($artister);$i++)
            {
              if($artister[$i][2]==1)
              {
                echo "<option style='text-align:center' value='".$artister[$i][0]."'>".$artister[$i][1]."</option>";
              }
            }
            echo "</select><br><br>";
        ?>
        <input type='submit' value='Lag gruppekobling' name="artist_koblingReg">
    </form>
  </body>
</html>
