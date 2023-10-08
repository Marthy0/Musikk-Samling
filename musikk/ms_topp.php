<nav class="medieNav">
  <div class="dropdown">
    <!--<button class="dropbtn">-->
      <button name="medieValg" class="dropbtn valg active" onclick="visMedietype('Vinyl')" id="Vinyl">Vinyl</button>
    <!--</button>-->
    <div class="dropdown-content">
      <button name="medieValg" class="valg" onclick="visMedietype('LP')" id="LP">LP</button>
      <button name="medieValg" class="valg" onclick="visMedietype('Singel')" id="Singel">Singel</button>
      <button name="medieValg" class="valg" onclick="visMedietype('EP')" id="EP">EP</button>
      <button name="medieValg" class="valg" onclick="visMedietype('78')" id="78">78</button>
    </div>
  </div>
  <div class="dropdown">
    <!--<button class="dropbtn">-->
      <button name="medieValg" class="dropbtn valg" onclick="visMedietype('Kassett')" id="Kassett">Kassett</button>
    <!--</button>-->
    <div class="dropdown-content">
      <!-- <input type="submit" name="medieValg" value="Philips kassett">
      <input type="submit" name="medieValg" value="8 spor"> -->
    </div>
  </div>
  <div class="dropdown">
    <!--<button class="dropbtn">-->
      <button name="medieValg" class="dropbtn valg" onclick="visMedietype('CD')" id="CD">CD</button>
    <!--</button>-->
    <div class="dropdown-content">

    </div>
  </div>
  <div class="dropdown">
    <!--<button class="dropbtn">-->
      <button name="medieValg" class="dropbtn valg" onclick="visMedietype('Annet')" id="Annet">Annet</button>
    <!--</button>-->
    <div class="dropdown-content">

    </div>
  </div>
  
  
  <!--<div class="dropdown" id="toppNavn">
    <button class="dropbtn">
      <a>Martins vinylsamling</a>
    </button>
  </div>-->
  
  
   <div class="dropdown" id="rediger">
      <button name="medieValg" class="dropbtn valg" onclick="document.location='rediger'">Rediger</button>
  </div>
  
  <!--<div class="dropdown" id="leggTil">
    <button class="dropbtn">
      <input type="submit" name="medieValg" value="Legg til">
    </button>
  </div>-->
</nav>

<script>
    var aktiv = document.getElementById("Vinyl"); 
    function visMedietype(medieType)
    {
        if (aktiv) aktiv.className = aktiv.className.replace(" active", "");
        aktiv = document.getElementById(medieType);
        aktiv.className += " active";
        document.getElementById("iramme").src="ms_albumenheter.php?medieType="+medieType;
    }
</script>
