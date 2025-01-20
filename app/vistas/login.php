<?php
session_start();
// require_once '../helpers/CookieHelper.php';

// $usuario_recordado = isset($_COOKIE['usuario']) ? $_COOKIE['usuario'] : '';
include_once '../solicitud_permisos/app/controladores/logincontroler.php';
$login = new LoginController();
if (!isset($_SESSION['correo']) || !isset($_SESSION['rol'])) {
    // Si no ha iniciado sesión, redirigir al login
    header("Location: /solicitud_de_permisos_laborales/app/views/login.php ");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/solicitud_permisos/app/assets/css/login.css">
    <link rel="stylesheet" href="/solicitud_permisos/app/assets/css/global.css">
    <!-- Incluyendo SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
     
    
</head>
<body>
    <main>
        <section id="content_form-login">
            <article id="content_figure_login">
                <figure id="figure_login">
                    <img src="/solicitud_permisos/app/assets/img/logoOficial.png" alt="Logo Centro De Formación Integral Providencia." id="img_login">
                </figure>
            </article>
            <h2 id="text_login">Inicia sesión</h2>
            <form id="form-login" method="POST" action=" ">
                <label for="usuario" class="label-login">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required pattern="[a-z0-9]{5,25}" tabindex="1" title="Ingrese su usuario"><br><br>
                <label for="password" class="label-login">Contraseña:</label>
                <input type="password" id="password" name="password" required pattern=".{9,}[*.#]$" tabindex="2" title="La contraseña debe tener minimo 8 caracteres y terminar con * o # o .">
                <button type="submit" tabindex="4">Iniciar sesión</button>
            </form>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                try {
                    $resultado = $login->iniciarSesion();
                    
                } catch (Exception $e) {
                    error_log($e->getMessage());
                    echo '<div class="alert alert-error">Error inesperado</div>';
                }
            }
            ?>
            
        </section>
    </main>
    


    <?php
    // Mostrar mensajes de SweetAlert2 si existen
    if (isset($_GET['titulo']) && isset($_GET['mensaje']) && isset($_GET['icono'])) {
        $titulo = $_GET['titulo'];
        $mensaje = $_GET['mensaje'];
        $icono = $_GET['icono'];
    
        // Mostrar el mensaje con SweetAlert2
        echo "<script>
            Swal.fire({
                title: '{$titulo}',
                text: '{$mensaje}',
                icon: '{$icono}',
                timer: '$timie',
                confirmButtonText: 'Aceptar'
            });
        </script>";
        unset($titulo, $mensaje, $icono);
    }
    ?>

</body>
</html>