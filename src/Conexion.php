<?php

namespace App;

use \PDO;
use \PDOException;

class Conexion
{
    protected static ?PDO $conexion = null;

    public function __construct()
    {
        self::crearConexion();
    }

    protected static function crearConexion(): void
    {
        if (self::$conexion != null) return;
        $dotenv =  \Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
        $dotenv->load();
        $user = $_ENV['USER'];
        $pass = $_ENV['PASSWORD'];
        $dbname = $_ENV['BASE'];
        $host = $_ENV['HOST'];

        $opciones = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

        try {
            self::$conexion = new PDO($dsn, $user, $pass, $opciones);
        } catch (PDOException $ex) {
            die("Error en la conexion: " . $ex->getMessage());
        }
    }
}
