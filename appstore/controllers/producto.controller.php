<?php
//Configurar la zonal local
date_default_timezone_set("America/Lima");

require_once '../models/Producto.php';

if (isset($_POST['operacion'])){

  $producto = new Producto();

  // ¿Que operación es?
  switch ($_POST['operacion']) {
    case 'listar':
      // EL método listar retorna un array PHP asociativo, esto no lo entiende el navegador
      // Entonces convertimos el arreglo en un objeto JSON y lo enviamos a la vista.
      echo json_encode($producto->listar());
      // render... ENVIAR ETIQUETAS / DATOS NAVEGADOR
      break;
    case 'registrar':

      //Generar un nombre a partir del momento exacto
      $ahora = date('dmYhis');
      $nombreArchivo = sha1($ahora) . ".jpg";

      // Recolectar / recibir los valores enviados desde la vista
      $datosEnviar = [
        'idcategoria'   => $_POST['idcategoria'],
        'descripcion'   => $_POST['descripcion'],
        'precio'        => $_POST['precio'],
        'garantia'      => $_POST['garantia'],
        'fotografia'    => ''
      ];

      //Solo movemos la imagen, si esta existe (uploaded)
      if (isset($_FILES['fotografia'])){
        if (move_uploaded_file($_FILES['fotografia']['tmp_name'], "../images/" . $nombreArchivo)){
          $datosEnviar["fotografia"] = $nombreArchivo;
        }
      }

      echo json_encode($producto->registrar($datosEnviar));
      break;
    case 'eliminar':
      $datosEnviar = ["idproducto" => $_POST['idproducto']];
      echo $producto->eliminar($datosEnviar);
      break;
    case 'filtrarCategoria':
      $datosEnviar = ["idcategoria" => $_POST["idcategoria"]];
      echo json_encode($producto->filtrarCategoria($datosEnviar));
      break;
  }

}