<?php
  function kobleTil($databasenavn="mthorst_no")//Funksjon for tilkobling av databasen
  {
    $vert = "mthorst.no.mysql";
    $bruker = "mthorst_no";
    $passord = "xGS2Txrv7NuScbR3wSWZsCzR";

    $db = new mysqli($vert, $bruker, $passord, $databasenavn);
    $db -> autocommit(TRUE);
    return $db; //skjer bare hvis alt gikk bra
  }
  
  function hentArtister()
  {
    $db = kobleTil();//Kobler til db
    $sql = "SELECT id, navn FROM artist_gruppe order by navn";
    $resultat = $db->query($sql);

    $artister = array();
    while($nesteRad = $resultat->fetch_assoc())
    {
      $artister[] = $nesteRad;
    }
    return $artister;
  }
  
    function hentArtisterSammensetning($gruppe)
  {
    $db = kobleTil();//Kobler til db
    $sql = "SELECT id, navn FROM artist_gruppe where gruppe != $gruppe order by navn";
    $resultat = $db->query($sql);

    $artister = array();
    while($nesteRad = $resultat->fetch_assoc())
    {
      $artister[] = $nesteRad;
    }
    return $artister;
  }
  
 function hentAlleArtister()
  {
    $db = kobleTil();//Kobler til db
    $sql = "SELECT id, navn, instrument, gruppe FROM artist_gruppe order by navn";
    $resultat = $db->query($sql);

    $artister = array();
    while($nesteRad = $resultat->fetch_assoc())
    {
      $artister[] = $nesteRad;
    }
    return $artister;
  }
  
   function hentSammenkoblede($artist_id, $gruppe)
  {
    $db = kobleTil();//Kobler til db
    if ($gruppe) $sql = "select a.id, navn, instrument from artist_gruppe a, artist_sammensetning s where a.id = s.artist_id and s.gruppe_id = ?";
    else $sql =         "select a.id, navn, instrument from artist_gruppe a, artist_sammensetning s where a.id = s.gruppe_id and s.artist_id = ?";
    $stmt = $db->prepare($sql);

    $stmt->bind_param("i", $artist_id);
    $stmt->execute();
    $resultat = $stmt->get_result();

    $artister = array();
    while($nesteRad = $resultat->fetch_assoc())
    {
      $artister[] = $nesteRad;
    }
    return $artister;
  }
  
    function hentAlbum($artist_id)
  {
    $albumHentet = array();
    $db = kobleTil();//Kobler til db
    $sql = "SELECT a.id, a.navn, tilstand, type, bilde_adresse, beskrivelse FROM artist_album aa, album a WHERE a.id = aa.album_id and aa.artist_id = ?";
    $stmt = $db->prepare($sql);

    $stmt->bind_param("i", $artist_id);
    $stmt->execute();
    
    $resultat = $stmt->get_result();
    while ($nesteRadAlb = $resultat->fetch_assoc())
    {
          $albumHentet[] = $nesteRadAlb;
    }
    return($albumHentet);
  }
  
    function hentAlbumUtenArtist()
  {
    $albumHentet = array();
    $db = kobleTil();//Kobler til db
    $sql = "SELECT a.id, a.navn, tilstand, type, bilde_adresse, beskrivelse FROM album a WHERE not exists (select 1 from artist_album aa where aa.album_id = a.id)";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    
    $resultat = $stmt->get_result();
    while ($nesteRadAlb = $resultat->fetch_assoc())
    {
          $albumHentet[] = $nesteRadAlb;
    }
    return($albumHentet);
  }

 
?>