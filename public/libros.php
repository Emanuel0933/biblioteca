<?php
declare(strict_types=1);
require __DIR__ . '/../includes/auth.php';
requerirLogin();
require __DIR__ . '/../config/db.php';

$busqueda = trim($_GET['q'] ?? '');

$sql = 'SELECT l.*, a.nombre AS autor, c.nombre AS categoria, e.nombre AS editorial
        FROM libros l
        JOIN autores a ON a.id = l.autor_id
        JOIN categorias c ON c.id = l.categoria_id
        JOIN editoriales e ON e.id = l.editorial_id';

if ($busqueda !== '') {
    $sql .= ' WHERE l.titulo LIKE :q OR a.nombre LIKE :q';
    $stmt = $pdo->prepare($sql . ' ORDER BY l.titulo');
    $stmt->execute(['q' => "%$busqueda%"]);
} else {
    $stmt = $pdo->query($sql . ' ORDER BY l.titulo');
}

$libros = $stmt->fetchAll();

$titulo = 'Libros';
require __DIR__ . '/../includes/header.php';
?>
<div class="tarjeta">
    <h1>Libros</h1>

    <form method="get" action="libros.php" style="display:flex; gap:8px; align-items:flex-end;">
        <div style="flex:1;">
            <label for="q">Buscar por título o autor</label>
            <input type="text" id="q" name="q" value="<?= htmlspecialchars($busqueda) ?>">
        </div>
        <button type="submit">Buscar</button>
    </form>

    <p style="margin-top:16px;"><a class="btn" href="libro_form.php">+ Nuevo libro</a></p>

    <table>
        <tr>
            <th>Título</th><th>Autor</th><th>Categoría</th><th>Editorial</th>
            <th>Año</th><th>ISBN</th><th>Stock</th><th>Acciones</th>
        </tr>
        <?php foreach ($libros as $l): ?>
        <tr>
            <td><?= htmlspecialchars($l['titulo']) ?></td>
            <td><?= htmlspecialchars($l['autor']) ?></td>
            <td><?= htmlspecialchars($l['categoria']) ?></td>
            <td><?= htmlspecialchars($l['editorial']) ?></td>
            <td><?= (int)$l['anio_publicacion'] ?></td>
            <td><?= htmlspecialchars($l['isbn']) ?></td>
            <td><?= (int)$l['stock'] ?></td>
            <td class="acciones">
                <a href="libro_form.php?id=<?= $l['id'] ?>">Editar</a>
                <a href="libro_eliminar.php?id=<?= $l['id'] ?>"
                   onclick="return confirm('¿Eliminar este libro?');">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$libros): ?>
        <tr><td colspan="8">No se encontraron libros.</td></tr>
        <?php endif; ?>
    </table>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
