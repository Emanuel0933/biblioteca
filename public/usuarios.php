<?php
declare(strict_types=1);
require __DIR__ . '/../includes/auth.php';
requerirAdmin();
require __DIR__ . '/../config/db.php';

$usuarios = $pdo->query('SELECT id, nombre, email, rol, fecha_registro FROM usuarios ORDER BY nombre')->fetchAll();

$titulo = 'Usuarios';
require __DIR__ . '/../includes/header.php';
?>
<div class="tarjeta">
    <h1>Usuarios registrados</h1>
    <table>
        <tr><th>Nombre</th><th>Correo</th><th>Rol</th><th>Registrado</th></tr>
        <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['nombre']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['rol']) ?></td>
            <td><?= htmlspecialchars($u['fecha_registro']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
