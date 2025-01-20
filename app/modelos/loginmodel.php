<?php
require_once __DIR__ . '/../../conexion.php';
class loginmodel {
    private $db;

    public function __construct()
    {
        $this->db = ConectService::conectar();
    }

    
    public function verificarCredenciales($usuario, $password) {
        // Consulta SQL corregida
        $sql = "SELECT nombres, apellidos, cedula, correo, id_departamento, rol,estado FROM usuarios WHERE usuario = ? AND contrasena = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Error en la preparación de la consulta: " . implode(":", $this->db->errorInfo()));
        }
        // Encriptar contraseña usando SHA512
        $password_hashed = hash("sha512", $password);
        // Ejecutar la consulta con los valores
        $stmt->execute([$usuario, $password_hashed]);
        // Obtener el resultado
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // Verificar si se encontró el usuario
        
        return $result ?: false;
    }

    // // Método para verificar si las credenciales existen
    //  // Verificar si el correo ya existe
    //  public function correoExiste($correo) {
    //     $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = ?");
    //     $stmt->execute([$correo]);
    //     return $stmt->fetchColumn() > 0;
    // }

    // // Verificar si la cédula ya existe
    // public function cedulaExiste($cedula) {
    //     $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE cedula = ?");
    //     $stmt->execute([$cedula]);
    //     return $stmt->fetchColumn() > 0;
    // }

    // // Verificar si el nombre de usuario ya existe
    // public function usuarioExiste($usuario) {
    //     $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = ?");
    //     $stmt->execute([$usuario]);
    //     return $stmt->fetchColumn() > 0;
    // }

    // // Registrar un nuevo usuario
    // public function registrarUsuario($nombres, $apellidos, $cedula, $correo, $departamento, $rol, $password, $usuario) {
    //     $stmt = $this->db->prepare("INSERT INTO usuarios (nombres, apellidos, cedula, correo, id_departamento, rol, contrasena, usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    //     return $stmt->execute([$nombres, $apellidos, $cedula, $correo, $departamento, $rol, $password, $usuario]);
    // }


    public function verificarDuplicado($campo, $valor) {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE $campo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$valor]);
        return $stmt->fetchColumn();
    }

    public function registrarUsuario($nombre, $apellido, $cedula, $correo, $departamento, $rol, $usuario, $password) {
        $sql = "INSERT INTO usuarios (nombre, apellido, cedula, correo, id_departamento, rol, contrasena, usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $apellido, $cedula, $correo, $departamento, $rol, $password, $usuario]);
    }

    public function identificarLider(){
        
    }

    public function envioSolicitudLiderProceso($id_departamento_solicitante){
        $sql = "SELECT usuarios.correo, usuarios.nombres FROM usuarios JOIN departamentos ON departamentos.id_lider = usuarios.id_usuario WHERE departamentos.id_lider = usuarios.id_usuario AND usuarios.id_departamento = '1' AND usuarios.rol = 'lider_aprobador';";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();

    }
}
