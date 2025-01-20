<?php
// encabezado
header("ACCESS-CONTROL-ALLOW-ORIGIN: *");
class ConectService {
    // private $pdo;

    
    // public function __construct() {
    //     $dbhost = "localhost";
    //     $dbport = "3306";
    //     $dbuser = "root";
    //     $dbpassword = "";
    //     // $dbname = "prueba_solicitud";
    //     $dbname = "sdp";

    //     $dsn = "mysql:host=$dbhost;dbname=$dbname;dbport=$dbport;; ";
    //     $options = [
    //         PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    //         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    //         PDO::ATTR_EMULATE_PREPARES   => false,
    //     ];

    //     try {
    //         $this->pdo = new PDO($dsn, $dbuser, $dbpassword, $options);
    //     } catch (\PDOException $e) {
    //         throw new \PDOException($e->getMessage(), (int)$e->getCode());
    //     }
    // }

    // public function getConnection() {
    //     return $this->pdo;
    // }


    static public function conectar()
    {
        // Conexion a BD SIIS Pg 15
        $contrasena = "";
        $usuario = "root";
        $BD = "sdp";
        // $BD = "solicitud_permisos";
        $rutaServidor = "localhost";
        $puerto = "3306";

        // Conexion a BD Sigho Pg 16
        // $contrasena = "123456789";
        // $usuario = "postgres";
        // $BD = "sigho";
        // $rutaServidor = "127.0.0.1";
        // $puerto = "5433";

        $base_de_datos = null;

        try {
            $base_de_datos = new PDO("mysql:host=$rutaServidor;port=$puerto;dbname=$BD", $usuario, $contrasena);
            $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Conecto con la base de datos ";
        } catch (PDOException $e) {
            throw new Exception("Error al conectar a la base de datos: " . $e->getMessage());
        }
        return  $base_de_datos;
    }

}
?>
