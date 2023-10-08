<?php
include "ms_db_inc.php";

$action = $_GET["action"];

if ($action == "getArtist") {
    $artist_id = $_GET["artist"];

    echo "<br>";
    if ($artist_id == 9999) {
        $album = hentAlbumUtenArtist($artist_id);
    } else {
        $album = hentAlbum($artist_id);
    }
    foreach ($album as $a) {
        $id = $a["id"];
        $navn = $a["navn"];
        $tilstand = $a["tilstand"];
        $bilde = $a["bilde_adresse"];
        $beskr = $a["beskrivelse"];
        $type = $a["type"];
        echo "<br><input type='text' id='album_navn_$id'  value='$navn' onkeyup='document.getElementById(\"album_lagre_$id\").hidden=false'>";
        echo "<input type='text' size='10' id='album_tilstand_$id'  value='$tilstand' placeholder='Tilstand' onkeyup='document.getElementById(\"album_lagre_$id\").hidden=false'>";
        echo "<input type='text' id='album_bilde_$id'  value='$bilde' placeholder='Bilde' onkeyup='document.getElementById(\"album_lagre_$id\").hidden=false'>";
        
        echo "<form action='upload.php' method='post' target='xxx' enctype='multipart/form-data' style='display: inline;' onsubmit='var filnavn = jpgExt(prompt(\"Filnavn\", document.getElementById(\"album_bilde_$id\").value));document.getElementById(\"album_bilde_$id\").value=filnavn; this.querySelector(\"#filnavn_$id\").value=filnavn;document.getElementById(\"album_lagre_$id\").hidden=false'>";
        echo "<input type='text' name='filnavn' id='filnavn_$id' hidden>";
        echo "<input type='file' name='image' id='nytt_album_upload_$id' hidden><input type='text' name='album_id' value='$id' hidden>";
        echo "<input type='button' onclick='this.hidden=\"true\";this.parentElement.querySelector(\"input[type=file]\").hidden=false;this.parentElement.querySelector(\"input[type=submit]\").hidden=false' value='Last'>";
        echo "<input type='submit' id='nytt_album_submit_$id' value='Last opp' name='submit' hidden>";
        echo "</form>";
        
        echo "<input type='text' id='album_beskr_$id'  value='$beskr' placeholder='Beskrivelse' onkeyup='document.getElementById(\"album_lagre_$id\").hidden=false'>";
        echo "<select id='album_type_$id' value='$type' onchange='document.getElementById(\"album_lagre_$id\").hidden=false'>";
        foreach (array("LP", "EP", "Singel", "Kassett", "CD", "Annet") as $t) {
            echo "<option value='$t'";
            if ($type == $t) {
                echo " selected";
            }
            echo ">$t</option>";
        }
        echo "</select>";

        if ($artist_id == 9999) {
            echo "<button onclick='slettAlbum($artist_id, $id,\"$navn\")'>Slett</button>";
        } else {
            echo "<button onclick='fjernKobling($artist_id, $id,\"$navn\")'>Fjern</button>";
        }

        echo "<button onclick='kobleAlbum($artist_id, $id,\"$navn\")'>Koble</button><span id='koble_$id'></span>";

        echo "<button id='album_lagre_$id' hidden='true' onclick='lagreAlbum($artist_id, $id, 
        document.getElementById(\"album_navn_$id\").value,
        document.getElementById(\"album_tilstand_$id\").value,
        document.getElementById(\"album_bilde_$id\").value,
        document.getElementById(\"album_beskr_$id\").value,
        document.getElementById(\"album_type_$id\").value,
        );document.getElementById(\"album_lagre_$id\").hidden=true'>Lagre</button>";
        echo "<A href='../ms_bildealbum_org/$bilde' target='cover'><img src='../ms_bildealbum/$bilde' width='20px' style='vertical-align: top;'></a>";

        // echo "<br>";
    }

    echo "<span id='nytt_album_$artist_id'></span>";


    // echo "<button onclick='lukkAlbum($artist_id)'>Lukk</button>";
    if ($artist_id != 9999) {
       echo "<br><button onclick='nyttAlbum($artist_id)'>Ny</button>";
    }
    echo "<br><br></p>";
}
else if ($action == "visSammensetninger") {
    $artist_id = $_GET["artist"];
    $gruppe = $_GET["gruppe"];

    echo "<p><br>";

    $artister = hentSammenkoblede($artist_id, $gruppe);
    foreach ($artister as $a) {
        $id = $a["id"];
        $navn = $a["navn"];
        $instrument = $a["instrument"];
        echo "<p>$navn - $instrument";
        echo "<button onclick='fjernArtistKobling($artist_id, $id,\"$navn\", $gruppe)'>Fjern</button>";
    }
    if ($gruppe) $knapp = "Legg til artist";
    else $knapp = "Legg til gruppe";
    echo "<p><button onclick='lagSammensetning($artist_id, $gruppe)'>$knapp</button><span id='sammensetning_$artist_id'><span>";
    // echo "<p><button onclick='lukkAlbum($artist_id)'>Lukk</button>";
    echo "<br><br></p>";
}
else if ($action == "fjernSammensetning") {
  $artist_id = $_GET["artist_id"];
  $gruppe_id = $_GET["gruppe_id"];
  $artist_navn = $_GET["artist_navn"];
  $gruppe_navn = $_GET["gruppe_navn"];

  $sql = "delete from artist_sammensetning where artist_id = ? and gruppe_id = ?";
  $db = kobleTil();
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ii", $artist_id, $gruppe_id);
  if ($stmt->execute()) {
      if ($stmt->affected_rows == 1) {
          echo "Artist '$artist_navn' er fjernet fra gruppa '$gruppe_navn'";
      } else {
          echo "Feil ved fjerning av '$artist_navn' fra gruppa '$gruppe_navn', antall rader oppdatert: " . $stmt->affected_rows . " skulle vært 1";
      }
  } else {
      echo "Fjerning av artist '$artist_navn' fra gruppa '$gruppe_navn' feilet\n$sql\n" . $db->error;
  }
}
else if ($action == "lagSammensetning") {
  $artist_id = $_GET["artist_id"];
  $gruppe = $_GET["gruppe"];

  $placeholder = "Velg " . ($gruppe ? "artist" : "gruppe");
  $artister = hentArtisterSammensetning($gruppe);
  echo "<select name='artKoblingReg' onchange='lagreSammensetning($artist_id, this.value, this.selectedOptions[0].text, $gruppe)'><option value='' hidden >$placeholder</option>";
  foreach($artister as $a)
  {
    echo "<option value='".$a["id"]."'>".$a["navn"]."</option>";
  }
  echo "</select>";
}
else if ($action == "lagreSammensetning") {
  $artist_id = $_GET["artist_id"];
  $gruppe_id = $_GET["gruppe_id"];
  $artist_navn = $_GET["artist_navn"];
  $gruppe_navn = $_GET["gruppe_navn"];

  $sql = "insert artist_sammensetning (artist_id, gruppe_id) values(?,?)";
  $db = kobleTil();
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ii", $artist_id, $gruppe_id);
  if ($stmt->execute()) {
      if ($stmt->affected_rows == 1) {
          echo "Artist '$artist_navn' er medlem i gruppa '$gruppe_navn'";
      } else {
          echo "Kobling av artist '$artist_navn' til gruppe '$gruppe_navn' feilet, antall rader oppdatert: " . $stmt->affected_rows . " skulle vært 1";
      }
  } else {
      echo "Kobling av artist '$artist_navn' til gruppe '$gruppe_navn' feilet\n$sql\n" . $db->error;
  }
}
else if ($action == "lagreArtist") {
  $id = $_GET["artist_id"];
  $navn = $_GET["navn"];
  $instrument = $_GET["instrument"];
  $gruppe = $_GET["gruppe"];

  $sql = "update artist_gruppe set navn = ?, instrument = ?, gruppe = ? where id = ?";
  $db = kobleTil();
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ssii", $navn, $instrument, $gruppe, $id);
  if ($stmt->execute()) {
      if ($stmt->affected_rows == 1) {
          echo "Artist $id - $navn - $instrument - $gruppe lagret OK!";
      } else {
          echo "Feil ved oppdatering av artist $navn, antall rader oppdatert: " . $stmt->affected_rows . " skulle vært 1";
      }
  } else {
      echo "Oppdatering av artist $navn feilet\n$sql\n" . $db->error;
  }
}
else if ($action == "lagreNyArtist") {
  $navn = $_GET["navn"];
  $instrument = $_GET["instrument"];
  $gruppe = $_GET["gruppe"];

  $sql = "insert artist_gruppe(navn, instrument, gruppe) values (?,?,?)";
  $db = kobleTil();
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ssi", $navn, $instrument, $gruppe);
  if ($stmt->execute()) {
      if ($stmt->affected_rows == 1) {
          $id = $db -> insert_id;
          echo "Artist $id - $navn - $instrument - $gruppe lagret OK!";
      } else {
          echo "Feil ved lagring av artist $navn, antall rader oppdatert: " . $stmt->affected_rows . " skulle vært 1";
      }
  } else {
      echo "Lagring av artist $navn feilet\n$sql\n" . $db->error;
  }

}
else if ($action == "slettArtist") {
  $artist_id = $_GET["artist_id"];
  $artist_navn = $_GET["artist_navn"];
  $db = kobleTil();
  $sql = "select sum(ant) from (select count(*) ant from artist_sammensetning where artist_id = ? or gruppe_id = ? UNION select count(*) ant from artist_album where artist_id = ?) a";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("iii", $artist_id, $artist_id, $artist_id);
  $stmt->execute();
  $stmt->store_result();
  if($stmt->num_rows === 0) exit('No rows');
  $stmt->bind_result($koblinger);
  $stmt->fetch();
  if ($koblinger > 0) exit("Artist $artist_navn er koblet til artist/gruppe eller har album, kan ikke slettes!");

  $sql = "delete from artist_gruppe where id = ?";

  $stmt = $db->prepare($sql);
  $stmt->bind_param("i", $artist_id);
  if ($stmt->execute()) {
      if ($stmt->affected_rows == 1) {
          echo "Artist '$artist_navn' er slettet";
      } else {
          echo "Feil ved sletting av '$artist_navn', antall rader oppdatert: " . $stmt->affected_rows . " skulle vært 1";
      }
  } else {
      echo "Sletting av artist '$artist_navn' feilet\n$sql\n" . $db->error;
  }
}
else if ($action == "lagreAlbum") {
  $id = $_GET["album_id"];
  $navn = $_GET["navn"];
  $tilstand = $_GET["tilstand"];
  $bilde = $_GET["bilde"];
  $beskr = $_GET["beskr"];
  $type = $_GET["type"];
  $sql = "update album set navn = ?, tilstand = ?, bilde_adresse = ?, beskrivelse = ?, type = ? where id = ?";
  $db = kobleTil();
  if (!$stmt = $db->prepare($sql)) echo $db->error;
  $stmt->bind_param("sssssi", $navn, $tilstand, $bilde, $beskr, $type, $id);

  if ($stmt->execute()) {
      if ($stmt->affected_rows == 1) {
         echo "Album $id - $navn - $tilstand - $bilde - $beskr - $type lagret OK!";
      } else {
          echo "Feil ved oppdatering av album $navn, antall rader oppdatert: " . $stmt->affected_rows . " skulle vært 1";
      }
   } else {
      echo "Oppdatering av album $navn feilet\n$sql\n" . $db->error;
   }
}
else if ($action == "lagreNyttAlbum") {
  $artist_id = $_GET["artist_id"];
    $artist_navn = $_GET["artist_navn"];

  $navn = $_GET["navn"];
  $tilstand = $_GET["tilstand"];
  $bilde = $_GET["bilde"];
  $beskr = $_GET["beskr"];
  $type = $_GET["type"];
  $sql = "insert album(navn, tilstand, bilde_adresse, beskrivelse, type) values (?,?,?,?,?)";
  $db = kobleTil();
  if (!$stmt = $db->prepare($sql)) echo $db->error;
  $stmt->bind_param("sssss", $navn, $tilstand, $bilde, $beskr, $type);

  if ($stmt->execute()) {
      if ($stmt->affected_rows == 1) {
         $album_id = $db -> insert_id;
         echo "Album $album_id - $navn - $tilstand - $bilde - $beskr - $type lagret OK!";

           $sql = "insert artist_album (artist_id, album_id) values(?,?)";
           $stmt = $db->prepare($sql);
           $stmt->bind_param("ii", $artist_id, $album_id);
           if ($stmt->execute()) {
              if ($stmt->affected_rows == 1) {
                   echo "\nAlbum '$navn' er koblet til artist '$artist_navn'";
              } else {
                   echo "Kobling av '$album_navn' til artist '$artist_navn' feilet, antall rader oppdatert: " . $stmt->affected_rows . " skulle vært 1";
             }
         } else {
                echo "Kobling av album '$album_navn' til artist '$artist_navn' feilet\n$sql\n" . $db->error;
         }

      } else {
          echo "Feil ved lagring av album $navn, antall rader oppdatert: " . $stmt->affected_rows . " skulle vært 1";
      }
   } else {
      echo "Lagring av album $navn feilet\n$sql\n" . $db->error;
   }
}
else if ($action == "fjernKobling") {
  $artist_id = $_GET["artist_id"];
  $album_id = $_GET["album_id"];
  $artist_navn = $_GET["artist_navn"];
  $album_navn = $_GET["album_navn"];

  $sql = "delete from artist_album where artist_id = ? and album_id = ?";
  $db = kobleTil();
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ii", $artist_id, $album_id);
  if ($stmt->execute()) {
      if ($stmt->affected_rows == 1) {
          echo "Album '$album_navn' er fjernet fra artist '$artist_navn'";
      } else {
          echo "Feil ved fjerning av '$album_navn' fra artist '$artist_navn', antall rader oppdatert: " . $stmt->affected_rows . " skulle vært 1";
      }
  } else {
      echo "Fjerning av album '$album_navn' fra artist '$artist_navn' feilet\n$sql\n" . $db->error;
  }
}
else if ($action == "slettAlbum") {
  $album_id = $_GET["album_id"];
  $album_navn = $_GET["album_navn"];

  $sql = "delete from album where id = ?";
  $db = kobleTil();
  $stmt = $db->prepare($sql);
  $stmt->bind_param("i", $album_id);
  if ($stmt->execute()) {
      if ($stmt->affected_rows == 1) {
          echo "Album '$album_navn' er slettet";
      } else {
          echo "Feil ved sletting av '$album_navn', antall rader oppdatert: " . $stmt->affected_rows . " skulle vært 1";
      }
  } else {
      echo "Sletting av album '$album_navn' feilet\n$sql\n" . $db->error;
  }
}
else if ($action == "kobleAlbum") {
  $artist_id = $_GET["artist_id"];
  $album_id = $_GET["album_id"];
//   $artist_navn = $_GET["artist_navn"];
  $album_navn = $_GET["album_navn"];

  $artister = hentArtister();
  echo "<select name='artKoblingReg' onchange='lagreKobling(this.value, $album_id, \"$album_navn\", $artist_id)'><option value='' hidden >Velg artist</option>";
  foreach($artister as $a)
  {
    echo "<option value='".$a["id"]."'>".$a["navn"]."</option>";
  }
  echo "</select>";
}
else if ($action == "lagreKobling") {
  $artist_id = $_GET["artist_id"];
  $album_id = $_GET["album_id"];
  $artist_navn = $_GET["artist_navn"];
  $album_navn = $_GET["album_navn"];

  $sql = "insert artist_album (artist_id, album_id) values(?,?)";
  $db = kobleTil();
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ii", $artist_id, $album_id);
  if ($stmt->execute()) {
      if ($stmt->affected_rows == 1) {
          echo "Album '$album_navn' er koblet til artist '$artist_navn'";
      } else {
          echo "Kobling av '$album_navn' til artist '$artist_navn' feilet, antall rader oppdatert: " . $stmt->affected_rows . " skulle vært 1";
      }
  } else {
      echo "Kobling av album '$album_navn' til artist '$artist_navn' feilet\n$sql\n" . $db->error;
  }
}
?>