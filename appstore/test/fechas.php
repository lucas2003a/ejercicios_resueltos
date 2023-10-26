<?php
date_default_timezone_set("America/Lima");
$ahora = date('dmYhis');
$nombreArchivo = sha1($ahora) . ".jpg";

//echo $nombreArchivo;
//INGENIERÍA SOCIAL

$personas = ["Juan", "Luis", "Miguel", "LuIs"];

for ($i = 0; $i < 4; $i++){
  $encriptado = sha1($personas[$i]);
  echo "<p>{$personas[$i]}, {$encriptado}</p>";
}