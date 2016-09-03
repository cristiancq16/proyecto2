<?php
// Se abre el .txt en modo de lectura al comienzo de la linea
$doc = fopen("Coordenadas.txt","r") or die ("Error al leer el archivo");
// comprueba que el puntero esta al final de la linea
  while(!feof($doc)){
    // Obtiene la linea
    $texto = fgets($doc);
    // Espacios convierte en string
    $textoS = nl2br($texto);
    echo $textoS;
   // Divide un string en varios string
    $ubicacion = explode(" ", $texto);
      $lat = $ubicacion[1];
      $log = $ubicacion[3];
      $fecha = $ubicacion[5];
      $hora = $ubicacion[7];
  }
  //Refresca la pagina cada 10 segundos
  //$url=$_SERVER['REQUEST_URI'];
  //header("Refresh: 10; URL=$url");
  fclose($doc);
?>

<?php
require("dbinfo.php");

$LatitudGPSS;
$LongitudGPSS;

function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}

// Opens a connection to a MySQL server
$connection=mysql_connect ($servername, $username, $password);
if (!$connection) {
  die('Not connected : ' . mysql_error());
}

// Set the active MySQL database
$db_selected = mysql_select_db($dbname, $connection);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysql_error());
}

// Select all the rows in the markers table
$query = "SELECT * FROM coordenadas ORDER BY id DESC LIMIT 1";
$result = mysql_query($query);
if (!$result) {
  die('Invalid query: ' . mysql_error());
}
while ($row = @mysql_fetch_assoc($result)){
  // ADD TO XML DOCUMENT NODE
    $LatitudGPSS=$row['latitud'];
    $LongitudGPSS=$row['longitud'];
}
//header("Content-type: text/xml");
//
//// Start XML file, echo parent node
//echo '<markers>';
//
//// Iterate through the rows, printing XML nodes for each
//while ($row = @mysql_fetch_assoc($result)){
//  // ADD TO XML DOCUMENT NODE
//  echo '<marker ';
//  echo 'lat="' . $row['latitud'] . '" ';
//  echo 'lng="' . $row['longitud'] . '" ';
//  echo '/>';
//}
//
//// End XML file
//echo '</markers>';

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Rastreo Satelital de Vehiculos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
    <!-- Se le proporciona los margenes que va a ocupar el cuerpo de la pagina -->
    <!-- Se le proporciona los margenes que va a ocupar el mapa -->
    <!-- css -->
    <style>
      html, body {
        height: 100%;
        margin: 0;
      }
      #map {
        height: 100%;
        width: 100%;
      } 
    </style>
  </head>
  <body>
    <div class="w3-container w3-teal">
    <h1>Rastreo Satelital de Vehiculos</h1>
    </div>
    
    <div id="map">

    <script type="text/javascript">
    var LatitudGPSS=<?php echo $LatitudGPSS?>;
    var LongitudGPSS=<?php echo $LongitudGPSS?>;
    </script>
    
    <script>
      var map;
      // Funcion para crear el mapa y brindarle los parametros necesarios para nuesta ubicacion
      // Tipo de mapa es el ROADMAP, que es el mapa basico 

      
      var interval = setInterval(initMap,5000);

      function initMap() {
        var myLatLng = {lat:LatitudGPSS , lng:LongitudGPSS};
        map = new google.maps.Map(document.getElementById('map'), {
          center: myLatLng,
          zoom: 15
        });

        
        // Parametros para centrar el mapa y darle un zoom adecuado
        var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        });
        // Apuntador con la ubicacion de las coordenadas
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDtxNxHoSNOl8JMvcc4KAIlkoSlURTKL54&callback=initMap"
    async defer></script>
    </div>

      
  </body>
</html>
