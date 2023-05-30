<?php
namespace App;

use \PDOException;
use \PDO;
use stdClass;

class Articulos extends Conexion{
    private string $nombre;
    private string $descripcion;
    private float $precio;
    private int $stock;

    public function __construct()
    {
        parent::__construct();
    }
    
    public static function readAll(): false|array
    {
        parent::crearConexion();
        $q="select * from articulos order by id desc";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){

        }
        parent::$conexion=null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function read(int $id): \stdClass
    {
        parent::crearConexion();
        $q="select * from articulos where id=:i";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':i'=>$id
            ]);
        }catch(PDOException $ex){

        }
        parent::$conexion=null;
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function create(){
        $q="insert into articulos(nombre, descripcion, precio, stock) values(:n, :d, :s, :p)";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':d'=>$this->descripcion,
                ':s'=>$this->stock,
                ':p'=>$this->precio,
            ]);
        }catch(PDOException $ex){
            die($ex->getMessage());
        }
        parent::$conexion=null;
    }

    public function update(int $id): void{
        $q="update articulos set nombre=:n, descripcion=:d, precio=:p, stock=:s where id=:i";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':n'=>$this->nombre,
                ':d'=>$this->descripcion,
                ':s'=>$this->stock,
                ':p'=>$this->precio,
                ':i'=>$id
            ]);
        }catch(PDOException $ex){
            die($ex->getMessage());
        }
        parent::$conexion=null;
    }

    public static function borrar(int $id): void{
        parent::crearConexion();
        $q="delete from articulos where id=:i";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':i'=>$id,
            ]);
        }catch(PDOException $ex){
            die($ex->getMessage());
        }
        parent::$conexion=null;
    }


    public static function existeNombre(string $nombre, ?int $id=null): bool{
        parent::crearConexion();
        $q=($id!=null) ? "select id from articulos where nombre=:n AND id!=:i" : "select id from articulos where nombre=:n";
        $options=($id!=null) ? [':i'=>$id, ':n'=>$nombre] : ['n'=>$nombre];
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute($options);
        }catch(PDOException $ex){
            die($ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }

    /**
     * Set the value of nombre
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Set the value of descripci
     */
    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Set the value of precio
     */
    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Set the value of stock
     */
    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }
}