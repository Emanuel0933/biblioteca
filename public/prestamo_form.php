<?php
declare(strict_types=1);
require __DIR__ . '/../includes/auth.php';
requerirLogin();
require __DIR__ . '/../config/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$prestamo = [
    'libro_id' => '', 'usuario_id' => '', 'fecha_prestamo' => date('Y-m-d'),
    'fecha_devolucion_esperada' => '', 'fecha_devolucion_real' => '', 'estado' => 'prestado',
];
$error = '';

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM prestamos WHERE id = ?');
    $stmt->execute([$id]);
    $encontrado = $stmt->fetch();
    if ($encontrado) {
        $prestamo = $encontrado;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prestamo = [
        'libro_id'                  => (int) ($_POST['libro_id'] ?? 0),
        'usuario_id'                => (int) ($_POST['usuario_id'] ?? 0),
        'fecha_prestamo'            => $_POST['fecha_prestamo'] ?? '',
        'fecha_devolucion_esperada' => $_POST['fecha_devolucion_esperada'] ?? '',
        'fecha_devolucion_real'     => $_POST['fecha_devolucion_real'] ?: null,
        'estado'                    => $_POST['estado'] ?? 'prestado',
    ];

    if (!$prestamo['libro_id'] || !$prestamo['usuario_id'] || !$prestamo['fecha_prestamo'] || !$prestamo['fecha_devolucion_esperada']) {
        $error = 'Completa todos los campos obligatorios.';
    } else {
        if ($id) {
            $stmt = $pdo->prepare(
                'UPDATE prestamos SET libro_id=?, usuario_id=?, fecha_prestamo=?, fecha_devolucion_esperada=?, fecha_devolucion_real=?, estado=? WHERE id=?'
            );
            $stmt->execute([...array_values($prestamo), $id]);
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, fecha_devolucion_esperada, fecha_devolucion_real, estado)
                 VALUES (?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute(array_values($prestamo));
        }
        header('Location: prestamos.php');
        exit;
    }
}

$libros   = $pdo->query('SELECT id, titulo FROM libros ORDER BY titulo')->fetchAll();
$usuarios = $pdo->query('SELECT id, nombre FROM usuarios ORDER BY nombre')->fetchAll();

$titulo = $id ? 'Editar préstamo' : 'Nuevo préstamo';
require __DIR__ . '/../includes/header.php';
?>
<div class="tarjeta">
    <h1><?= $id ? 'Editar préstamo' : 'Nuevo préstamo' ?></h1>

    <?php if ($error): ?><div class="alerta-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post" action="prestamo_form.php<?= $id ? '?id=' . $id : '' ?>">
        <label for="libro_id">Libro</label>
        <select id="libro_id" name="libro_id" required>
            <option value="">-- Selecciona --</option>
            <?php foreach ($libros as $l): ?>
                <option value="<?= $l['id'] ?>" <?= $l['id'] == $prestamo['libro_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($l['titulo']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="usuario_id">Usuario</label>
        <select id="usuario_id" name="usuario_id" required>
            <option value="">-- Selecciona --</option>
            <?php foreach ($usuarios as $u): ?>
                <option value="<?= $u['id'] ?>" <?= $u['id'] == $prestamo['usuario_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="fecha_prestamo">Fecha de préstamo</label>
        <input type="date" id="fecha_prestamo" name="fecha_prestamo" required
               value="<?= htmlspecialchars($prestamo['fecha_prestamo']) ?>">

        <label for="fecha_devolucion_esperada">Fecha de devolución esperada</label>
        <input type="date" id="fecha_devolucion_esperada" name="fecha_devolucion_esperada" required
               value="<?= htmlspecialchars($prestamo['fecha_devolucion_esperada']) ?>">

        <label for="fecha_devolucion_real">Fecha de devolución real (opcional)</label>
        <input type="date" id="fecha_devolucion_real" name="fecha_devolucion_real"
               value="<?= htmlspecialchars($prestamo['fecha_devolucion_real'] ?? '') ?>">

        <label for="estado">Estado</label>
        <select id="estado" name="estado" required>
            <?php foreach (['prestado', 'devuelto', 'atrasado'] as $op): ?>
                <option value="<?= $op ?>" <?= $op === $prestamo['estado'] ? 'selected' : '' ?>><?= ucfirst($op) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit"><?= $id ? 'Guardar cambios' : 'Registrar préstamo' ?></button>
        <a class="btn" href="prestamos.php" style="background:#888;">Cancelar</a>
    </form>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
