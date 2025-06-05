<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/signupModel.php');

class CrudUsuario {
    private $conn;

    public function __construct() {
        $this->conn = Db::conectar();
    }

    public function verificarUsuarioPorCorreo($correo, $contrasenia) {
        $stmt = $this->conn->prepare("SELECT id_usuario, nombre_usuario, correo, rol, sancionado FROM usuarios WHERE correo = :correo AND contrasenia = :contrasenia");
        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':contrasenia', $contrasenia, PDO::PARAM_STR);
        $stmt->execute();

        if ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Usuario($fila['id_usuario'], $fila['nombre_usuario'], $fila['correo'], $fila['rol'], $fila['sancionado']);
        }

        return null;
    }
}
?>
