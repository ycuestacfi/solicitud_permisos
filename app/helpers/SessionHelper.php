<?php
class SessionHelper {
    public static function iniciarSesion($usuario) {
        session_start();
        $_SESSION['nombres'] = $usuario['nombres'];
        $_SESSION['apellidos'] = $usuario['apellidos'];
        $_SESSION['cedula'] = $usuario['cedula'];
        $_SESSION['correo'] = $usuario['correo'];
        $_SESSION['id_departamento'] = $usuario['id_departamento'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['estado'] = $usuario['estado'];
    }

    public static function cerrarSesion() {
        session_start();
        session_unset();
        session_destroy();
    }

}
?>