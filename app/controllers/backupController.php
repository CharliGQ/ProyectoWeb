<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'main_owner') {
    header('Location: ../login.php');
    exit();
}

// Simular backup
sleep(1);

$_SESSION['success'] = "Backup generado correctamente.";
header('Location: ../views/dashboard/admin.php?seccion=backup');
exit();
?>