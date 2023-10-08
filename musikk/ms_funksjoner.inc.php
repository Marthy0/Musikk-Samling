<?php

  //medieverdier
//   $medieNavn = array();
//   $medieTilstand = array();
//   $medieBilde = array();
//   $medieTilstand = array();
//   $medieBeskrivelse = array();
//   $medier = array();
//   $medier = array($medieNavn, $medieTilstand, $medieBilde, $medieTilstand, $medieBeskrivelse);

    function forbokstav($navn) {
        $n = str_replace("The ", "", $navn);
        return iconv_substr($n, 0, 1);
    }

  // kobleTil();
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
    // $sql = "SELECT id, navn FROM artist_gruppe ORDER BY navn";
    $sql = "SELECT id, navn FROM artist_gruppe ORDER BY replace(navn, 'The ', '')";
    $resultat = $db->query($sql);

    $artister = array();
    while($nesteRad = $resultat->fetch_assoc())
    {
      $artister[] = array($nesteRad['id'], $nesteRad['navn']);
    }
    // print_r($artister);
    return $artister;
    // $artister = array("Abba", "Dire Straits","The Beatles", "The Rolling Stones", "Vazelina Bilopphøggers", "Øistein Sunde");
  }

    function hentArtistNavn($artist_id)
    {
      $db = kobleTil();//Kobler til db
      $sql = "SELECT navn FROM artist_gruppe WHERE id = ?";
      $stmt = $db->prepare($sql);

      $stmt->bind_param("i", $artist_id);
      $stmt->execute();

      $resultat = $stmt->get_result();
      while($nesteRad = $resultat->fetch_assoc())
      {
        return $nesteRad['navn'];
      }
    }

  function andreArtister($album_id)
      {
        $db = kobleTil();//Kobler til db
        $sql = "SELECT ag.id, ag.navn FROM artist_album aa, artist_gruppe ag WHERE aa.artist_id = ag.id AND album_id = ?";
        $stmt = $db->prepare($sql);

        $stmt->bind_param("i", $album_id);
        $stmt->execute();

        $resultat = $stmt->get_result();
        $artister = "Artister:";
        while($nesteRad = $resultat->fetch_assoc())
        {
          $artister .= " <A href=?artistValg={$nesteRad['id']}>{$nesteRad['navn']}</A>";
        }
        return $artister;
      }

  $albumHentet = array();
  function hentAlbum($artist_id, $medieType)
  {
    $albumHentet = array();
    $db = kobleTil();//Kobler til db
    if (is_numeric($artist_id))
    {
    $sql = "SELECT a.id, a.navn, tilstand, type, bilde_adresse, beskrivelse FROM artist_album aa, album a WHERE a.id = aa.album_id and aa.artist_id = ? ORDER BY a.navn";
    $par = $artist_id;
    }
    else
    {
    $sql = "SELECT a.id, a.navn, tilstand, type, bilde_adresse, beskrivelse FROM artist_album aa, album a, artist_gruppe ag WHERE a.id = aa.album_id and aa.artist_id = ag.id and ag.navn like ? ORDER BY a.navn";
    $par = $artist_id."%";
    }
    $stmt = $db->prepare($sql);

    $stmt->bind_param("s", $par);
    $stmt->execute();
    
    $resultat = $stmt->get_result();
    while ($nesteRadAlb = $resultat->fetch_assoc())
    {
        if($medieType==$nesteRadAlb['type']||$medieType=="Vinyl")
        {
          $albumHentet[] = array($nesteRadAlb['id'], $nesteRadAlb['navn'], $nesteRadAlb['tilstand'], $nesteRadAlb['bilde_adresse'], $nesteRadAlb['beskrivelse'], $nesteRadAlb['type']);
        }
    }
    // if(!empty($albumHentet))
    // {
    return($albumHentet);
    // }
    // print_r($albumHentet);//Returnerer reisen
  }

  function hentArtGru_kobling($artist_id)
  {
    $db = kobleTil();//kobler til databasen
    $valgtArtist = $artist_id;//lagrer valgt artist
    //Henter artist info fra db
    $sql = "SELECT navn, gruppe FROM artist_gruppe WHERE id = ?";//
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $valgtArtist);
    $stmt->execute();
    $resultat = $stmt->get_result();
    $stmt->close();

    while($nesteRad = $resultat->fetch_assoc())
    {
      $navn = $nesteRad['navn'];
      if($nesteRad['gruppe']==0)
      {
        $sql = "SELECT gr.id, gr.navn FROM artist_sammensetning asa, artist_gruppe gr WHERE asa.gruppe_id = gr.id and asa.artist_id = ?";
        $artGru_kobling = "Andre grupper tilhørende $navn: ";
      } else {
        $artGru_kobling = "Andre artister tilhørende $navn: ";
        $sql = "SELECT art.id, art.navn FROM artist_sammensetning asa, artist_gruppe art WHERE asa.artist_id = art.id and asa.gruppe_id = ?";
      }

      $stmt = $db->prepare($sql);
      $stmt->bind_param("s", $valgtArtist);
      $stmt->execute();
      $resultat2 = $stmt->get_result();
        while($nesteRad2 = $resultat2->fetch_assoc())
        {
          $artGru_kobling = $artGru_kobling." <A href = ?artistValg=".$nesteRad2['id'].">".$nesteRad2['navn']."</A>";
        }

    }
    return $artGru_kobling;
  }
?>
