<?php
declare(strict_types=1);
require __DIR__ . '/../includes/auth.php';
requerirLogin();
require __DIR__ . '/../config/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$libro = [
    'titulo' => '', 'autor_nombre' => '', 'categoria_id' => '',
    'editorial_id' => '', 'anio_publicacion' => '', 'isbn' => '', 'stock' => 1,
];
$error = '';

if ($id) {
    $stmt = $pdo->prepare(
        'SELECT l.*, a.nombre AS autor_nombre
         FROM libros l JOIN autores a ON a.id = l.autor_id
         WHERE l.id = ?'
    );
    $stmt->execute([$id]);
    $encontrado = $stmt->fetch();
    if ($encontrado) {
        $libro = $encontrado;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $libro = [
        'titulo'           => trim($_POST['titulo'] ?? ''),
        'autor_nombre'     => trim($_POST['autor_nombre'] ?? ''),
        'categoria_id'     => (int) ($_POST['categoria_id'] ?? 0),
        'editorial_id'     => (int) ($_POST['editorial_id'] ?? 0),
        'anio_publicacion' => (int) ($_POST['anio_publicacion'] ?? 0),
        'isbn'             => trim($_POST['isbn'] ?? ''),
        'stock'            => (int) ($_POST['stock'] ?? 0),
    ];

    if ($libro['titulo'] === '' || $libro['autor_nombre'] === '' || $libro['isbn'] === '' || !$libro['categoria_id'] || !$libro['editorial_id']) {
        $error = 'Completa todos los campos obligatorios.';
    } else {
        // Busca el autor por nombre; si no existe, lo crea al vuelo
        $stmtAutor = $pdo->prepare('SELECT id FROM autores WHERE nombre = ?');
        $stmtAutor->execute([$libro['autor_nombre']]);
        $autor = $stmtAutor->fetch();

        if ($autor) {
            $autorId = (int) $autor['id'];
        } else {
            $stmtNuevoAutor = $pdo->prepare(
                'INSERT INTO autores (nombre, nacionalidad, fecha_nacimiento) VALUES (?, ?, ?)'
            );
            $stmtNuevoAutor->execute([$libro['autor_nombre'], 'No especificada', '1900-01-01']);
            $autorId = (int) $pdo->lastInsertId();
        }

        $datos = [
            $libro['titulo'], $autorId, $libro['categoria_id'], $libro['editorial_id'],
            $libro['anio_publicacion'], $libro['isbn'], $libro['stock'],
        ];

        if ($id) {
            $stmt = $pdo->prepare(
                'UPDATE libros SET titulo=?, autor_id=?, categoria_id=?, editorial_id=?, anio_publicacion=?, isbn=?, stock=? WHERE id=?'
            );
            $stmt->execute([...$datos, $id]);
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO libros (titulo, autor_id, categoria_id, editorial_id, anio_publicacion, isbn, stock)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute($datos);
        }
        header('Location: libros.php');
        exit;
    }
}

$autoresExistentes = $pdo->query('SELECT nombre FROM autores ORDER BY nombre')->fetchAll();
$categorias        = $pdo->query('SELECT id, nombre FROM categorias ORDER BY nombre')->fetchAll();
$editoriales       = $pdo->query('SELECT id, nombre FROM editoriales ORDER BY nombre')->fetchAll();

$titulo = $id ? 'Editar libro' : 'Nuevo libro';
require __DIR__ . '/../includes/header.php';
?>
<div class="form-libro-header">
    <h1><?= $id ? ' Editar libro' : ' Agregar nuevo libro' ?></h1>
    <p>Completa los datos del libro para <?= $id ? 'actualizarlo en' : 'agregarlo a' ?> el catálogo.</p>
</div>

<div class="tarjeta form-libro">
    <?php if ($error): ?><div class="alerta-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post" action="libro_form.php<?= $id ? '?id=' . $id : '' ?>">
        <div class="form-grid">
            <div class="campo campo-ancho">
                <label for="imagen_url">URL de imagen de portada (opcional)</label>
                <input type="text" id="imagen_url" name="imagen_url" placeholder="https://..."
                    value="<?= htmlspecialchars($libro['imagen_url'] ?? '') ?>">
                <small class="ayuda-campo">Si lo dejas vacío, se mostrará un ícono de color según la categoría.</small>
            </div>

            <div class="campo">
                <label for="autor_nombre">Autor</label>
                <input type="text" id="autor_nombre" name="autor_nombre" list="autores-lista" required
                       placeholder="Escribe el nombre del autor..."
                       value="<?= htmlspecialchars($libro['autor_nombre']) ?>">
                <datalist id="autores-lista">
                    <?php foreach ($autoresExistentes as $a): ?>
                        <option value="<?= htmlspecialchars($a['nombre']) ?>">
                    <?php endforeach; ?>
                </datalist>
                <small class="ayuda-campo">Si el autor no existe, se creará automáticamente.</small>
            </div>

            <div class="campo">
                <label for="categoria_id">Categoría</label>
                <select id="categoria_id" name="categoria_id" required>
                    <option value="">-- Selecciona --</option>
                    <?php foreach ($categorias as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $c['id'] == $libro['categoria_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo">
                <label for="editorial_id">Editorial</label>
                <select id="editorial_id" name="editorial_id" required>
                    <option value="">-- Selecciona --</option>
                    <?php foreach ($editoriales as $e): ?>
                        <option value="<?= $e['id'] ?>" <?= $e['id'] == $libro['editorial_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($e['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo">
                <label for="anio_publicacion">Año de publicación</label>
                <input type="number" id="anio_publicacion" name="anio_publicacion" min="1000" max="2100" required
                       value="<?= htmlspecialchars((string)$libro['anio_publicacion']) ?>">
            </div>

            <div class="campo">
                <label for="isbn">ISBN</label>
                <input type="text" id="isbn" name="isbn" required value="<?= htmlspecialchars($libro['isbn']) ?>">
            </div>

            <div class="campo">
                <label for="stock">Stock disponible</label>
                <input type="number" id="stock" name="stock" min="0" required value="<?= htmlspecialchars((string)$libro['stock']) ?>">
            </div>
        </div>

        <div class="form-libro-acciones">
            <button type="submit"><?= $id ? '💾 Guardar cambios' : ' Crear libro' ?></button>
            <a class="btn btn-cancelar" href="libros.php">Cancelar</a>
        </div>
    </form>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>