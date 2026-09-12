<?php
declare(strict_types=1);
require __DIR__ . '/../includes/auth.php';
requerirLogin();
require __DIR__ . '/../config/db.php';

$error = '';
$editando = null;

// Eliminar
if (isset($_GET['eliminar'])) {
    $stmt = $pdo->prepare('DELETE FROM categorias WHERE id = ?');
    $stmt->execute([(int) $_GET['eliminar']]);
    header('Location: categorias.php');
    exit;
}

// Cargar para edición
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare('SELECT * FROM categorias WHERE id = ?');
    $stmt->execute([(int) $_GET['editar']]);
    $editando = $stmt->fetch();
}

// Guardar (alta o edición)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $id = (int) ($_POST['id'] ?? 0);

    if ($nombre === '') {
        $error = 'El nombre de la categoría es obligatorio.';
    } elseif ($id) {
        $stmt = $pdo->prepare('UPDATE categorias SET nombre=?, descripcion=? WHERE id=?');
        $stmt->execute([$nombre, $descripcion, $id]);
        header('Location: categorias.php');
        exit;
    } else {
        $stmt = $pdo->prepare('INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)');
        $stmt->execute([$nombre, $descripcion]);
        header('Location: categorias.php');
        exit;
    }
}

$categorias = $pdo->query('SELECT * FROM categorias ORDER BY nombre')->fetchAll();

$titulo = 'Categorías';
require __DIR__ . '/../includes/header.php';
?>
<div class="tarjeta">
    <h1><?= $editando ? 'Editar categoría' : 'Nueva categoría' ?></h1>

    <?php if ($error): ?><div class="alerta-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post" action="categorias.php">
        <input type="hidden" name="id" value="<?= $editando['id'] ?? '' ?>">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" required
               value="<?= htmlspecialchars($editando['nombre'] ?? '') ?>">

        <label for="descripcion">Descripción</label>
        <input type="text" id="descripcion" name="descripcion"
               value="<?= htmlspecialchars($editando['descripcion'] ?? '') ?>">

        <button type="submit"><?= $editando ? 'Guardar cambios' : 'Agregar categoría' ?></button>
        <?php if ($editando): ?><a class="btn" href="categorias.php" style="background:#888;">Cancelar</a><?php endif; ?>
    </form>
</div>

<div class="tarjeta">
    <h2>Listado de categorías</h2>
    <table>
        <tr><th>Nombre</th><th>Descripción</th><th>Acciones</th></tr>
        <?php foreach ($categorias as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['nombre']) ?></td>
            <td><?= htmlspecialchars($c['descripcion'] ?? '') ?></td>
            <td class="acciones">
                <a href="categorias.php?editar=<?= $c['id'] ?>">Editar</a>
                <a href="categorias.php?eliminar=<?= $c['id'] ?>"
                   onclick="return confirm('¿Eliminar esta categoría?');">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
