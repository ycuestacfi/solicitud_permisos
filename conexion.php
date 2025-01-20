<?php
// encabezado
header("ACCESS-CONTROL-ALLOW-ORIGIN: *");
class ConectService {

    static public function conectar()
    {
  
        $contrasena = "";
        $usuario = "root";
        $BD = "sdp";
        $rutaServidor = "localhost";
        $puerto = "3306";
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
