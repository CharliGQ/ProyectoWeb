<?php
require_once('../../models/adminModel.php');
$adminModel = new AdminModel();
$usuarios = $adminModel->getAllUsuarios();
?>

<h3>Gestión de Usuarios</h3>

<table class="table-users">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Sancionado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $user): ?>
            <tr>
                <td><?= $user['id_usuario'] ?></td>
                <td><?= htmlspecialchars($user['nombre_usuario']) ?></td>
                <td><?= htmlspecialchars($user['correo']) ?></td>
                <td><?= ucfirst($user['rol']) ?></td>
                <td><?= $user['sancionado'] ? 'Sí' : 'No' ?></td>
                <td>
                    <a href="../../controllers/usuarioController.php?action=sancionar&id=<?= $user['id_usuario'] ?>" class="btn-table"><?= $user['sancionado'] ? 'Quitar sanción' : 'Sancionar' ?></a>
                    <a href="../../controllers/usuarioController.php?action=cambiar_rol&rol=moderador&id=<?= $user['id_usuario'] ?>" class="btn-table">Hacer Moderador</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>