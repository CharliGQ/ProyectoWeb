<?php
require_once(__DIR__ . '/../config/database.php');

class AdminModel {
    // Obtener cantidad total de usuarios
    public function getTotalUsuarios() {
        $db = Db::conectar();
        $stmt = $db->query("SELECT COUNT(*) as total FROM usuarios");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    // Obtener cantidad de moderadores
    public function getTotalModeradores() {
        $db = Db::conectar();
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'moderador'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    // Obtener reportes activos (ejemplo: estado = 'activo')
    public function getTotalReportesActivos() {
        $db = Db::conectar();
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM reportes_comentarios WHERE estado = 'activo'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    public function getAllUsuarios() {
    $db = Db::conectar();
    $stmt = $db->query("SELECT * FROM usuarios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getModeradores() {
    $db = Db::conectar();
    $stmt = $db->query("SELECT * FROM usuarios WHERE rol = 'moderador'");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getUsuariosSinModerador() {
    $db = Db::conectar();
    $stmt = $db->query("SELECT * FROM usuarios WHERE rol != 'moderador'");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}

?>