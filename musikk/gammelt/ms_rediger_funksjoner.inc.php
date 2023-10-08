<?php
  if(isset($_GET['artistReg']))
  {
    leggTilArtGru();
  }
  function leggTilArtGru()
  {
    $navn = $_GET['artReg_navn'];
    $instrument = $_GET['artReg_instrument'];
    $gruppe = $_GET['artReg_gruppe'];

    $db = kobleTil();
    $sql = "INSERT INTO artist_gruppe (navn, instrument, gruppe) VALUES (?,?,?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssi", $navn, $instrument, $gruppe);

     if ($stmt->execute() === TRUE)
      {
        echo "Artist/gruppe lagret OK";
      } else {
        echo "Lagring av Artist/gruppe feilet!";
      }
  }

  if(isset($_GET['albumReg']))
  {
    leggTilAlbum();
  }
  function leggTilAlbum()
  {
    $navn = $_GET['albReg_navn'];
    $tilstand = $_GET['albReg_tilstand'];
    $type = $_GET['albReg_type'];
    $bilde_adresse = $_GET['albReg_bilde'];
    $beskrivelse = $_GET['albReg_beskrivelse'];

    $db = kobleTil();//Kjører funksjonen for å koble opp til db
    //Kjører insert spørring for å legge inn veriene i db
    $sql = "INSERT INTO album (navn, tilstand, type, bilde_adresse, beskrivelse) VALUES (?,?,?,?,?) ";
     $stmt = $db->prepare($sql);
     $stmt->bind_param("sssss", $navn, $tilstand, $type, $bilde_adresse, $beskrivelse);
     if ($stmt->execute() === TRUE)
      {
        echo "Album lagret OK";
      } else {
        echo "Lagring av album feilet!";
      }
  }

  function hentAlbumNavnId()
  {
    $db = kobleTil();//Kobler til db
    $sql = "SELECT id, navn FROM album";
    $resultat = $db->query($sql);//lagrer resultatene fra databasespørringen

    while($nesteRad = $resultat->fetch_assoc())
    {
      $albumHentetNavnId[] = array($nesteRad['id'], $nesteRad['navn']);
    }
    return($albumHentetNavnId);
  }

  if(isset($_GET['artist_albumReg']))
  {
    koble_artAlb();
  }
  function koble_artAlb()
  {
    $artist_id = $_GET['artKoblingReg'];
    $album_id = $_GET['albKoblingReg'];

    $db = kobleTil();//Kjører funksjonen for å koble opp til db
    //Kjører insert spørring for å legge inn veriene i db
    $sql = "INSERT INTO artist_album (artist_id, album_id) VALUES (?,?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $artist_id, $album_id);
    if ($stmt->execute() === TRUE)
    {
      echo "Album koblet til artist/gruppe";
    } else {
      echo "Kobling av album til artist/gruppe feilet!";
    }
  }

  function hentArtisterNavnGru()
  {
    $db = kobleTil();//Kobler til db
    $sql = "SELECT id, navn, gruppe FROM artist_gruppe";
    $resultat = $db->query($sql);//lagrer resultatene fra databasespørringen

    $artister = array();
    while($nesteRad = $resultat->fetch_assoc())
    {
      $artister[] = array($nesteRad['id'], $nesteRad['navn'], $nesteRad['gruppe']);
    }
    // print_r($artister);
    return $artister;
    // $artister = array("Abba", "Dire Straits","The Beatles", "The Rolling Stones", "Vazelina Bilopphøggers", "Øistein Sunde");
  }

  if(isset($_GET['artist_koblingReg']))
  {
    koble_artGru();
  }
  function koble_artGru()
  {
    $artist_id = $_GET['artKoblingReg'];
    $gruppe_id = $_GET['gruKoblingReg'];

    $db = kobleTil();//Kjører funksjonen for å koble opp til db
    //Kjører insert spørring for å legge inn veriene i db
    $sql = "INSERT INTO artist_sammensetning (artist_id, gruppe_id) VALUES (?,?)";
     $stmt = $db->prepare($sql);
     $stmt->bind_param("ii", $artist_id, $gruppe_id);
     if ($stmt->execute() === TRUE)
      {
        echo "Artist koblet til gruppe";
      } else {
        echo "Kobling av artist til gruppe feilet!";
      }
  }
  
  
  
  
  function hentValgtArtistOpp()
  {
    $db = kobleTil();//Kobler til db
    $sql = "SELECT navn, instrument, gruppe FROM artist_gruppe WHERE id='".$_SESSION['artistOpp_id']."'";
    $resultat = $db->query($sql);

    while($nesteRad = $resultat->fetch_assoc())
    {
        $artist = array($nesteRad['navn'], $nesteRad['instrument'], $nesteRad['gruppe']);
    }
    return $artist;
  }
 
    if(isset($_GET['artistOpp']))
    {
        oppdaterArtist();
    }
    function oppdaterArtist()
    {
        $navn = $_GET['artOpp_navn'];
        $instrument = $_GET['artOpp_instrument'];
        $gruppe = $_GET['artOpp_gruppe'];
        echo $navn;
        echo $instrument;
        echo $gruppe;
        echo $_SESSION["artistOpp_id"];
        
        $db = kobleTil();
      	$sql = "UPDATE artist_gruppe SET "
      		. "navn = '" . $navn . "', "
      		. "instrument = '"  . $instrument . "', "
            . "gruppe = '"  . $gruppe . "'"
      		. " WHERE id = '" . $_SESSION['artistOpp_id'] ."'";
      	echo $sql;
      	$db->query($sql);
    }
    
    
    function hentAlbumOpp()
    {
        $db = kobleTil();//Kobler til db
        $sql = "SELECT id, navn FROM album order by navn";
        $resultat = $db->query($sql);
    
        $artister = array();
        while($nesteRad = $resultat->fetch_assoc())
        {
          $album[] = array($nesteRad['id'], $nesteRad['navn']);
        }
        return $album;
    }
    
    function hentValgtAlbumOpp()
    {
        $db = kobleTil();//Kobler til db
        $sql = "SELECT navn, tilstand, type, bilde_adresse, beskrivelse FROM album WHERE id='".$_SESSION['albumOpp_id']."'";
        $resultat = $db->query($sql);

        while($nesteRad = $resultat->fetch_assoc())
        {
            $album = array($nesteRad['navn'], $nesteRad['tilstand'], $nesteRad['type'], $nesteRad['bilde_adresse'], $nesteRad['beskrivelse']);
        }
        return $album;
    }
    
    if(isset($_GET['albumOpp']))
    {
        oppdaterAlbum();
    }
    function oppdaterAlbum()
    {
        $navn = $_GET['albOpp_navn'];
        $tilstand = $_GET['albOpp_tilstand'];
        $type = $_GET['albOpp_type'];
        $bildeAdr = $_GET['albOpp_bildeAdr'];
        $beskrivelse = $_GET['albOpp_beskrivelse'];
        echo $navn;
        echo $tilstand;
        echo $type;
        echo $bildeAdr;
        echo $beskrivelse;
        echo $_SESSION["albumOpp_id"];//hvorfor tom
        
        $db = kobleTil();
      	$sql = "UPDATE album SET "
      		. "navn = '" . $navn . "', "
      		. "tilstand = '"  . $tilstand . "', "
      		. "type = '"  . $type . "', "
      		. "bilde_adresse = '"  . $bildeAdr . "', "
            . "beskrivelse = '"  . $beskrivelse . "'"
      		. " WHERE id = '" . $_SESSION['albumOpp_id'] ."'";
      	echo $sql;
      	$db->query($sql);
    }
 
?>