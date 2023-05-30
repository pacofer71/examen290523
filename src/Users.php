<?php
namespace App;
use \PDO;
use \PDOException;

class Users extends Conexion{
    private string $email;
    private string $pass;
    private bool $is_admin;

    public function __construct()
    {
        parent::__construct();
    }
    
    public static function comprobarLogin($email, $pass): int{ //devolveremos 0 si validacion mal 1 si bien y normal 2 si admin
        parent::crearConexion();
        $q="select is_admin, pass from users where email=:e";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':e'=>$email,
            ]);
        }catch(PDOException $ex){
            die($ex->getMessage());
        }
        parent::$conexion=null;
        $fila=$stmt->fetch(PDO::FETCH_OBJ);
        if($fila){
            if(!password_verify($pass, $fila->pass)) return 0;
            return ($fila->is_admin) ?  2 : 1 ;
        }
        return 0;

    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

   

    /**
     * Set the value of is_admin
     */
    public function setIsAdmin(bool $is_admin): self
    {
        $this->is_admin = $is_admin;

        return $this;
    }

    /**
     * Set the value of password
     */
    public function setPass(string $password): self
    {
        $this->pass = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }
}