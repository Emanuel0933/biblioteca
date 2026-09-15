<?php
declare(strict_types=1);
require __DIR__ . '/../includes/auth.php';
requerirLogin();
require __DIR__ . '/../config/db.php';

$prestamos = $pdo->query(
    'SELECT p.*, l.titulo AS libro, u.nombre AS usuario
     FROM prestamos p
     JOIN libros l ON l.id = p.libro_id
     JOIN usuarios u ON u.id = p.usuario_id
     ORDER BY p.fecha_prestamo DESC'
)->fetchAll();

function claseEstado(string $estado): string
{
    return match ($estado) {
        'devuelto' => 'estado-devuelto',
        'atrasado' => 'estado-atrasado',
        default    => 'estado-prestado',
    };
}

$titulo = 'Préstamos';
require __DIR__ . '/../includes/header.php';
?>
<div class="admin-header tema-morado">
    <h1> Préstamos</h1>
    <p>Consulta y administra los préstamos de libros a los usuarios.</p>
</div>

<div class="tarjeta">
    <div class="libros-encabezado">
        <p class="libros-contador"><?= count($prestamos) ?> préstamo<?= count($prestamos) === 1 ? '' : 's' ?> registrado<?= count($prestamos) === 1 ? '' : 's' ?></p>
        <a class="btn btn-morado" href="prestamo_form.php">+ Nuevo préstamo</a>
    </div>
</div>

<?php if ($prestamos): ?>
<div class="prestamos-grid">
    <?php foreach ($prestamos as $p): ?>
    <div class="prestamo-card">
        <div class="prestamo-card-top">
            <span class="badge <?= claseEstado($p['estado']) ?>"><?= htmlspecialchars(ucfirst($p['estado'])) ?></span>
        </div>
        <h3 class="prestamo-libro"> <?= htmlspecialchars($p['libro']) ?></h3>
        <p class="prestamo-usuario"> <?= htmlspecialchars($p['usuario']) ?></p>
        <div class="prestamo-fechas">
            <div><span>Prestado</span><strong><?= htmlspecialchars($p['fecha_prestamo']) ?></strong></div>
            <div><span>Devolución esperada</span><strong><?= htmlspecialchars($p['fecha_devolucion_esperada']) ?></strong></div>
            <div><span>Devolución real</span><strong><?= htmlspecialchars($p['fecha_devolucion_real'] ?? '—') ?></strong></div>
        </div>
        <div class="libro-acciones">
            <a href="prestamo_form.php?id=<?= $p['id'] ?>" class="btn btn-pequeno btn-morado">Editar</a>
            <a href="prestamo_eliminar.php?id=<?= $p['id'] ?>" class="btn btn-pequeno btn-eliminar"
               onclick="return confirm('¿Eliminar este préstamo?');">Eliminar</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="tarjeta"><p>No hay préstamos registrados.</p></div>
<?php endif; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>