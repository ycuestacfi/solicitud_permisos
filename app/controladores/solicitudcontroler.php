<?php
require_once __DIR__ . '/../models/solicitudmodel.php';

class SolicitudController {
    private $solicitudModel;

    public function __construct() {
        $this->solicitudModel = new solicitudmodel();
    }

    public function solicitudesRealizadas($cedula, $id_departamento) {
        // Si no se proporciona la cédula, retorna un array vacío
        if (!$cedula) {
            return [];
        }
    
        try {
            // Obtén el líder asociado al proceso
            $lider = $this->solicitudModel->lideres_proceso($id_departamento);
    
            // Obtén las solicitudes realizadas por el usuario
            $soli = $this->solicitudModel->solicitudes_realizadas($cedula);
    
            // Procesa el resultado para combinar
            $resultado = [
                'solicitudes' => $soli,
                'lider' => $lider,
            ];
    
            return $resultado;
    
        } catch (Exception $e) {
            // Registra el error en el log y retorna un array vacío
            error_log("Error en solicitudesRealizadas: " . $e->getMessage());
            return [];
        }
    }

    public function lideresProceso($id_departamento) {
        if (!$id_departamento) {
            return json_encode(['error' => 'El ID del departamento es obligatorio']);
        }

        try {
            $result = $this->solicitudModel->lideres_proceso($id_departamento);
            if ($result) {
                return json_encode(['success' => true, 'data' => $result]);
            }
            return json_encode(['error' => 'No se encontró líder para este departamento']);
        } catch (Exception $e) {
            error_log("Error en lideresProceso: " . $e->getMessage());
            return json_encode(['error' => 'Error al procesar la solicitud']);
        }
    }

    public function procesarFormulario() {
        // Variables comunes
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $cedula = $_POST['cedula'];
        $departamento = $_POST['departamento'];
        $fecha_de_solicitud = $_POST['fecha_de_solicitud'];
        $fecha_de_permiso = $_POST['fecha_de_permiso'];
        $hora_de_salida = $_POST['hora_de_salida'];
        $hora_de_llegada = $_POST['hora_de_llegada'];
        $observaciones = $_POST['observaciones'];
        $evidencias = $_FILES['evidencias']; // Para manejar archivos
        $tipo_permiso = $_POST['tipo_permiso'];
    
        // Variables específicas para permisos laborales
        $motivo_del_desplazamiento = "";
        $departamento_de_desplazamiento = "";
        $municipio_del_desplazamiento = "";
        $lugar_desplazamiento = "";
        $medio_de_transporte = "";
        $placa_vehiculo = "";
    
        // Asignar valores específicos si el tipo de permiso es "laboral"
        if ($tipo_permiso === 'laboral') {
            $motivo_del_desplazamiento = $_POST['motivo_del_desplazamiento'];
            $departamento_de_desplazamiento = $_POST['departamento_de_desplazamiento'];
            $municipio_del_desplazamiento = $_POST['municipio_del_desplazamiento'];
            $lugar_desplazamiento = $_POST['lugar_desplazamiento'];
            $medio_de_transporte = $_POST['medio_de_transporte'];
    
            if ($medio_de_transporte === 'AUTOMOVIL' || $medio_de_transporte === 'MOTOCICLETA') {
                $placa_vehiculo = $_POST['placa_vehiculo'];
            }
        }
    
        try {
            // Llamar al modelo para registrar la solicitud con todos los campos
            $registroExitoso = $this->solicitudModel->registrarSolicitud(
                $nombre,
                $email,
                $cedula,
                $departamento,
                $fecha_de_solicitud,
                $fecha_de_permiso,
                $hora_de_salida,
                $hora_de_llegada,
                $observaciones,
                $tipo_permiso,
                $motivo_del_desplazamiento,
                $departamento_de_desplazamiento,
                $municipio_del_desplazamiento,
                $lugar_desplazamiento,
                $medio_de_transporte,
                $placa_vehiculo,
                $evidencias
            );
    
            if ($registroExitoso) {
                // Obtener el email del líder del proceso
                $email_lider = $this->solicitudModel->lideres_proceso($departamento);
                if ($email_lider) {
                    $this->solicitudModel->enviarCorreo($nombre, $email_lider, $tipo_permiso);
                    return header("Location: /solicitud_permisos/app/vistas/dashboard.php");
                }
            }
    
            return $registroExitoso;
        } catch (Exception $e) {
            error_log("Error en procesarFormulario: " . $e->getMessage());
            return false;
        }
    }
    
}