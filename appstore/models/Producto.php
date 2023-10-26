<?php

require_once 'Conexion.php';

class Producto extends Conexion{
  private $conexion;

  public function __CONSTRUCT(){
    $this->conexion = parent::getConexion();
  }

  public function listar(){
    try {
      $consulta = $this->conexion->prepare("CALL spu_productos_listar()");
      $consulta->execute();
      return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(Exception $e){
      die($e->getMessage()); //Desarrollo > Producción
    }
  }

  public function eliminar($datos = []){
    try {
      $consulta = $this->conexion->prepare("CALL spu_productos_eliminar(?)");
      $status = $consulta->execute(
        array(
          $datos['idproducto']
        )
      );
      return $status;
    }
    catch(Exception $e){
      die($e->getMessage()); //Desarrollo > Producción
    }
  }

  public function filtrarCategoria($datos = []){
    try {
      $consulta = $this->conexion->prepare("CALL spu_productos_filtrar_categoria(?)");
      $consulta->execute(
        array(
          $datos['idcategoria']
        )
      );
      return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(Exception $e){
      die($e->getMessage()); 
    }
  }

  public function registrar($datos = []){
    try {
      $consulta = $this->conexion->prepare("CALL spu_productos_registrar(?,?,?,?,?)");
      $consulta->execute(
        array(
          $datos['idcategoria'],
          $datos['descripcion'],
          $datos['precio'],
          $datos['garantia'],
          $datos['fotografia']
        )
      );
      return $consulta->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }

}

