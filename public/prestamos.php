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

$titulo = 'Préstamos';
require __DIR__ . '/../includes/header.php';
?>
<div class="tarjeta">
    <h1>Préstamos</h1>
    <p><a class="btn" href="prestamo_form.php">+ Nuevo préstamo</a></p>

    <table>
        <tr>
            <th>Libro</th><th>Usuario</th><th>Fecha préstamo</th>
            <th>Devolución esperada</th><th>Devolución real</th><th>Estado</th><th>Acciones</th>
        </tr>
        <?php foreach ($prestamos as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['libro']) ?></td>
            <td><?= htmlspecialchars($p['usuario']) ?></td>
            <td><?= htmlspecialchars($p['fecha_prestamo']) ?></td>
            <td><?= htmlspecialchars($p['fecha_devolucion_esperada']) ?></td>
            <td><?= htmlspecialchars($p['fecha_devolucion_real'] ?? '—') ?></td>
            <td><?= htmlspecialchars(ucfirst($p['estado'])) ?></td>
            <td class="acciones">
                <a href="prestamo_form.php?id=<?= $p['id'] ?>">Editar</a>
                <a href="prestamo_eliminar.php?id=<?= $p['id'] ?>"
                   onclick="return confirm('¿Eliminar este préstamo?');">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$prestamos): ?>
        <tr><td colspan="7">No hay préstamos registrados.</td></tr>
        <?php endif; ?>
    </table>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
