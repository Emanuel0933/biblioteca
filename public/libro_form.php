<?php
declare(strict_types=1);
require __DIR__ . '/../includes/auth.php';
requerirLogin();
require __DIR__ . '/../config/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$libro = [
    'titulo' => '', 'autor_id' => '', 'categoria_id' => '',
    'editorial_id' => '', 'anio_publicacion' => '', 'isbn' => '', 'stock' => 1,
];
$error = '';

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM libros WHERE id = ?');
    $stmt->execute([$id]);
    $encontrado = $stmt->fetch();
    if ($encontrado) {
        $libro = $encontrado;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $libro = [
        'titulo'           => trim($_POST['titulo'] ?? ''),
        'autor_id'         => (int) ($_POST['autor_id'] ?? 0),
        'categoria_id'     => (int) ($_POST['categoria_id'] ?? 0),
        'editorial_id'     => (int) ($_POST['editorial_id'] ?? 0),
        'anio_publicacion' => (int) ($_POST['anio_publicacion'] ?? 0),
        'isbn'             => trim($_POST['isbn'] ?? ''),
        'stock'            => (int) ($_POST['stock'] ?? 0),
    ];

    if ($libro['titulo'] === '' || $libro['isbn'] === '' || !$libro['autor_id'] || !$libro['categoria_id'] || !$libro['editorial_id']) {
        $error = 'Completa todos los campos obligatorios.';
    } else {
        if ($id) {
            $stmt = $pdo->prepare(
                'UPDATE libros SET titulo=?, autor_id=?, categoria_id=?, editorial_id=?, anio_publicacion=?, isbn=?, stock=? WHERE id=?'
            );
            $stmt->execute([...array_values($libro), $id]);
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO libros (titulo, autor_id, categoria_id, editorial_id, anio_publicacion, isbn, stock)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute(array_values($libro));
        }
        header('Location: libros.php');
        exit;
    }
}

$autores     = $pdo->query('SELECT id, nombre FROM autores ORDER BY nombre')->fetchAll();
$categorias  = $pdo->query('SELECT id, nombre FROM categorias ORDER BY nombre')->fetchAll();
$editoriales = $pdo->query('SELECT id, nombre FROM editoriales ORDER BY nombre')->fetchAll();

$titulo = $id ? 'Editar libro' : 'Nuevo libro';
require __DIR__ . '/../includes/header.php';
?>
<div class="tarjeta">
    <h1><?= $id ? 'Editar libro' : 'Nuevo libro' ?></h1>

    <?php if ($error): ?><div class="alerta-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post" action="libro_form.php<?= $id ? '?id=' . $id : '' ?>">
        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo" required value="<?= htmlspecialchars($libro['titulo']) ?>">

        <label for="autor_id">Autor</label>
        <select id="autor_id" name="autor_id" required>
            <option value="">-- Selecciona --</option>
            <?php foreach ($autores as $a): ?>
                <option value="<?= $a['id'] ?>" <?= $a['id'] == $libro['autor_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($a['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="categoria_id">Categoría</label>
        <select id="categoria_id" name="categoria_id" required>
            <option value="">-- Selecciona --</option>
            <?php foreach ($categorias as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id'] == $libro['categoria_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="editorial_id">Editorial</label>
        <select id="editorial_id" name="editorial_id" required>
            <option value="">-- Selecciona --</option>
            <?php foreach ($editoriales as $e): ?>
                <option value="<?= $e['id'] ?>" <?= $e['id'] == $libro['editorial_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($e['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="anio_publicacion">Año de publicación</label>
        <input type="number" id="anio_publicacion" name="anio_publicacion" min="1000" max="2100" required
               value="<?= htmlspecialchars((string)$libro['anio_publicacion']) ?>">

        <label for="isbn">ISBN</label>
        <input type="text" id="isbn" name="isbn" required value="<?= htmlspecialchars($libro['isbn']) ?>">

        <label for="stock">Stock disponible</label>
        <input type="number" id="stock" name="stock" min="0" required value="<?= htmlspecialchars((string)$libro['stock']) ?>">

        <button type="submit"><?= $id ? 'Guardar cambios' : 'Crear libro' ?></button>
        <a class="btn" href="libros.php" style="background:#888;">Cancelar</a>
    </form>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
