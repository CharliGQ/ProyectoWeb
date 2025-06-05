<?php
require_once('../../models/adminModel.php');
$adminModel = new AdminModel();
$moderadores = $adminModel->getModeradores();
$usuariosSinModerador = $adminModel->getUsuariosSinModerador();
?>

<h3>Asignar Moderadores</h3>

<h4>Moderadores Actuales</h4>
<ul>
    <?php foreach ($moderadores as $m): ?>
        <li><?= htmlspecialchars($m['nombre_usuario']) ?> - <?= htmlspecialchars($m['correo']) ?></li>
    <?php endforeach; ?>
</ul>

<h4>Agregar Nuevo Moderador</h4>
<form action="../../controllers/usuarioController.php" method="POST">
    <input type="hidden" name="action" value="asignar_moderador">
    <select name="id_usuario" required>
        <option value="">Selecciona un usuario</option>
        <?php foreach ($usuariosSinModerador as $u): ?>
            <option value="<?= $u['id_usuario'] ?>"><?= htmlspecialchars($u['nombre_usuario']) ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="btn-table">Asignar Moderador</button>
</form>