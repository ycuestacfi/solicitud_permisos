<?php
require_once __DIR__ . '/../../conexion.php';
class solicitudmodel {
    private $db;

    public function __construct()
    {
        $this->db = ConectService::conectar();
    }

     // Método para registrar una solicitud en la base de datos
     public function registrarSolicitud($nombre, $email, $departamento, $fecha_solicitud, $fecha_permiso, $hora_salida, $hora_llegada, $observaciones, $tipo_permiso) {
        // Consulta para insertar los datos en la tabla de solicitudes
        $sql = "INSERT INTO solicitudes (nombres, email, departamento, fecha_solicitud, fecha_permiso, hora_salida, hora_llegada, observaciones, tipo_permiso) 
                VALUES (:nombre, :email, :departamento, :fecha_solicitud, :fecha_permiso, :hora_salida, :hora_llegada, :observaciones, :tipo_permiso)";
        
        $stmt = $this->db->prepare($sql);
        
        // Enlace de parámetros
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':departamento', $departamento);
        $stmt->bindParam(':fecha_solicitud', $fecha_solicitud);
        $stmt->bindParam(':fecha_permiso', $fecha_permiso);
        $stmt->bindParam(':hora_salida', $hora_salida);
        $stmt->bindParam(':hora_llegada', $hora_llegada);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':tipo_permiso', $tipo_permiso);

        // Ejecutar la consulta
        return $stmt->execute();
    }

    // Método para enviar un correo con la información de la solicitud
    public function enviarCorreo($nombre, $email_lider, $tipo_permiso) {
        $to = $email_lider; // Destinatario
        $subject = "Confirmación de Solicitud de Permiso";
        $message = "
        <html>
        <head>
        <title>Solicitud de Permiso</title>
        </head>
        <body>
        <h2>Hola, $nombre</h2>
        <p>Tu solicitud de permiso de tipo <strong>$tipo_permiso</strong> ha sido recibida correctamente.</p>
        <p>Recibirás una notificación sobre su estado pronto.</p>
        </body>
        </html>
        ";

        // Para enviar el correo en formato HTML
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: no-reply@tudominio.com" . "\r\n"; // Cambia a tu dominio

        return mail($to, $subject, $message, $headers);
    }

    public function solicitudes_realizadas($cedula){
        $sql = "SELECT * FROM solicitudes WHERE cedula = ?";
        $smtp = $this->db->prepare($sql);
        $smtp->execute([$cedula]);
        return $smtp->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lideres_correo($id_departamento) {
        if ($id_departamento) {
            $sql = "SELECT usuarios.correo, usuarios.nombres 
                    FROM usuarios 
                    INNER JOIN departamentos ON usuarios.id_usuario = departamentos.id_lider 
                    WHERE departamentos.id_departamento = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_departamento]);
            return $stmt->fetchColumn(); // Retorna un solo valor (correo del líder)
        }
        return null; // Retorna null si no hay departamento
    }

    public function lideres_proceso($id_departamento){
        if (isset($id_departamento)){
            $sql = "SELECT usuarios.correo,usuarios.nombres  FROM usuarios INNER JOIN departamentos ON usuarios.id_usuario = departamentos.id_lider INNER JOIN usuarios AS usuarios2 ON usuarios2.id_departamento = departamentos.id_departamento WHERE usuarios2.id_usuario = ? ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_departamento]);
            return $stmt->fetchColumn();
        }
    }


    public function registrarUsuario($nombres, $apellidos, $cedula, $correo, $departamento, $rol, $password, $usuario) {
            $stmt = $this->db->prepare("INSERT INTO usuarios (nombres, apellidos, cedula, correo, id_departamento, rol, contrasena, usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            return $stmt->execute([$nombres, $apellidos, $cedula, $correo, $departamento, $rol, $password, $usuario]);
    }

    

}
?>    