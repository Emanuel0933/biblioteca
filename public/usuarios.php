<?php
declare(strict_types=1);
require __DIR__ . '/../includes/auth.php';
requerirAdmin();
require __DIR__ . '/../config/db.php';

$usuarios = $pdo->query('SELECT id, nombre, email, rol, fecha_registro FROM usuarios ORDER BY nombre')->fetchAll();

$titulo = 'Usuarios';
require __DIR__ . '/../includes/header.php';
?>
<div class="admin-header tema-morado">
    <h1>Usuarios</h1>
    <p>Cuentas registradas en el sistema.</p>
</div>

<div class="tarjeta">
    <p class="libros-contador"><?= count($usuarios) ?> usuario<?= count($usuarios) === 1 ? '' : 's' ?> registrado<?= count($usuarios) === 1 ? '' : 's' ?></p>
</div>

<div class="usuarios-grid">
    <?php foreach ($usuarios as $u): ?>
    <div class="usuario-card">
        <div class="usuario-avatar"><?= strtoupper(substr($u['nombre'], 0, 1)) ?></div>
        <div class="usuario-info">
            <h3><?= htmlspecialchars($u['nombre']) ?></h3>
            <p><?= htmlspecialchars($u['email']) ?></p>
            <span class="badge <?= $u['rol'] === 'admin' ? 'badge-admin' : 'badge-usuario' ?>"><?= htmlspecialchars($u['rol']) ?></span>
            <p class="usuario-fecha">Desde <?= htmlspecialchars($u['fecha_registro']) ?></p>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>