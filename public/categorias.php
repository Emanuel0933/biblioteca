<?php
declare(strict_types=1);
require __DIR__ . '/../includes/auth.php';
requerirLogin();
require __DIR__ . '/../config/db.php';

$error = '';
$editando = null;

if (isset($_GET['eliminar'])) {
    $stmt = $pdo->prepare('DELETE FROM categorias WHERE id = ?');
    $stmt->execute([(int) $_GET['eliminar']]);
    header('Location: categorias.php');
    exit;
}

if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare('SELECT * FROM categorias WHERE id = ?');
    $stmt->execute([(int) $_GET['editar']]);
    $editando = $stmt->fetch();
}

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
<div class="admin-header tema-morado">
    <h1> Categorías</h1>
    <p>Administra los géneros literarios disponibles en el catálogo.</p>
</div>

<div class="tarjeta form-admin borde-morado">
    <h2><?= $editando ? ' Editar categoría' : ' Nueva categoría' ?></h2>

    <?php if ($error): ?><div class="alerta-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post" action="categorias.php">
        <input type="hidden" name="id" value="<?= $editando['id'] ?? '' ?>">
        <div class="form-grid">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required value="<?= htmlspecialchars($editando['nombre'] ?? '') ?>">
            </div>
            <div class="campo">
                <label for="descripcion">Descripción</label>
                <input type="text" id="descripcion" name="descripcion" value="<?= htmlspecialchars($editando['descripcion'] ?? '') ?>">
            </div>
        </div>
        <div class="form-libro-acciones">
            <button type="submit" class="btn-morado-solido"><?= $editando ? ' Guardar cambios' : '✅ Agregar categoría' ?></button>
            <?php if ($editando): ?><a class="btn btn-cancelar" href="categorias.php">Cancelar</a><?php endif; ?>
        </div>
    </form>
</div>

<div class="categorias-grid">
    <?php foreach ($categorias as $c): ?>
    <div class="categoria-card-admin">
        <h3><?= htmlspecialchars($c['nombre']) ?></h3>
        <p><?= htmlspecialchars($c['descripcion'] ?? 'Sin descripción.') ?></p>
        <div class="libro-acciones">
            <a href="categorias.php?editar=<?= $c['id'] ?>" class="btn btn-pequeno btn-morado">Editar</a>
            <a href="categorias.php?eliminar=<?= $c['id'] ?>" class="btn btn-pequeno btn-eliminar"
               onclick="return confirm('¿Eliminar esta categoría?');">Eliminar</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>