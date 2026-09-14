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

function claseStock(int $stock): string
{
    if ($stock <= 0) return 'stock-agotado';
    if ($stock <= 3) return 'stock-bajo';
    return 'stock-disponible';
}

$titulo = 'Libros';
require __DIR__ . '/../includes/header.php';
?>
<div class="tarjeta">
    <div class="libros-encabezado">
        <div>
            <h1>📚 Catálogo de libros</h1>
            <p class="libros-contador"><?= count($libros) ?> libro<?= count($libros) === 1 ? '' : 's' ?> encontrado<?= count($libros) === 1 ? '' : 's' ?></p>
        </div>
        <a class="btn" href="libro_form.php">+ Nuevo libro</a>
    </div>

    <form method="get" action="libros.php" class="libros-buscador">
        <input type="text" name="q" placeholder="Buscar por título o autor..." value="<?= htmlspecialchars($busqueda) ?>">
        <button type="submit">🔍 Buscar</button>
        <?php if ($busqueda !== ''): ?>
            <a href="libros.php" class="libros-limpiar">Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<?php