<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['correo']) || !isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit();
}
require_once __DIR__ . '/../controller/solicitudController.php';

// Ahora, pasar la conexión a la clase solicitudModel
$solicitudController = new SolicitudController();

// Obtener solicitudes
$cedula = $_SESSION['cedula'];
$id_departamento = $_SESSION['id_departamento'];
$solicitudes = $solicitudController->solicitudesRealizadas($cedula,$id_departamento);

$lider_proceso = $solicitudes['lider'];
$respuesta_solicitudes = $solicitudes['solicitudes'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Solicitudes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/solicitud_permisos/app/assets/css/style.css">
</head>
<body>
    <main>
        <section id="navigation">
            <nav>
                <figure style="margin:0; padding:0; width:150px;">
                    <a href="dashboard.php">
                        <img src="/solicitud_permisos/app/assets/img/logocfipblanco.png" style="width: 100%;" alt="">
                    </a>
                </figure>
                <div id="btn_menu">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                
                <ul id="menu">
                    <li><a href="dashboard.php">Inicio</a></li>
                    <li><a href="solicitudes.php">Mis solicitudes</a></li>
                    <li><a href="departamentos.php">Departamentos</a></li>
                    <li><a href="solicitud_de_permisos.php">Nueva solicitud</a></li>
                    <li><a href="rechazadas.php">Rechazadas</a></li>
                    <?php if ($_SESSION['rol'] == 'administrador'){ echo '<li><a href="register.php"> Registrar Usuarios</a></li>'; } ?>
                    <li><a href="/cierre_de_sesion.php" id="btn_salir">Cerrar sesión</a></li>
                </ul>
                <ul id="contenedor_btn_salir">
                    <li><a href="/solicitud_permisos/cierre_de_sesion.php" id="btn_salir">Cerrar sesión</a></li>
                </ul>
            </nav>
        </section>

        <table id="tabla_registros">
            <thead>
                <tr>
                    <th>Nombre del solicitante</th>
                    <th>Líder Aprobador</th>
                    <th>Fecha Solicitud</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($respuesta_solicitudes) ): ?>
                    <?php foreach ($respuesta_solicitudes as $solicitud): ?>
                    <tr>
                        <td class="td_solicitud">
                            <?php echo htmlspecialchars($_SESSION['nombres'] . ' ' . $_SESSION['apellidos']); ?>
                        </td>
                        <td class="td_solicitud"><?php echo htmlspecialchars($lider_proceso['nombre']); ?></td>
                        <td class="td_solicitud"><?php echo htmlspecialchars($solicitud['fecha_solicitud']); ?></td>
                        <td class="td_solicitud"><?php echo htmlspecialchars($solicitud['estado']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No se encontraron solicitudes. Puedes realizar una nueva.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2024 Copyright: Aviso de privacidad, Términos y condiciones. Todos los derechos reservados.</p>
    </footer>
    <script src="/solicitud_permisos/app/assets/js/main.js"></script>
    <script src="/solicitud_permisos/app/assets/js/menu.js"></script>
</body>
</html>