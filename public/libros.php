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

function colorPortada(string $categoria): int
{
    return crc32($categoria) % 6;
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
        <?php if (esAdmin()): ?>
            <a class="btn" href="libro_form.php">+ Nuevo libro</a>
        <?php endif; ?>
    </div>

    <form method="get" action="libros.php" class="libros-buscador">
        <input type="text" name="q" placeholder="Buscar por título o autor..." value="<?= htmlspecialchars($busqueda) ?>">
        <button type="submit">🔍 Buscar</button>
        <?php if ($busqueda !== ''): ?>
            <a href="libros.php" class="libros-limpiar">Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<?php if ($libros): ?>
<div class="libros-grid">
    <?php foreach ($libros as $l): ?>
    <div class="libro-card">
        <div class="libro-portada portada-<?= colorPortada($l['categoria']) ?>">
            <span class="libro-portada-icono">📖</span>
            <span class="badge badge-categoria-sobre-portada"><?= htmlspecialchars($l['categoria']) ?></span>
        </div>

        <div class="libro-card-body">
            <h3 class="libro-titulo"><?= htmlspecialchars($l['titulo']) ?></h3>
            <p class="libro-autor">✍️ <?= htmlspecialchars($l['autor']) ?></p>

            <div class="libro-detalles">
                <span>🏢 <?= htmlspecialchars($l['editorial']) ?></span>
                <span>📅 <?= (int)$l['anio_publicacion'] ?></span>
            </div>
            <p class="libro-isbn">ISBN: <?= htmlspecialchars($l['isbn']) ?></p>

            <span class="badge <?= claseStock((int)$l['stock']) ?>">
                <?= (int)$l['stock'] > 0 ? (int)$l['stock'] . ' en stock' : 'Agotado' ?>
            </span>

            <?php if (esAdmin()): ?>
            <div class="libro-acciones">
                <a href="libro_form.php?id=<?= $l['id'] ?>" class="btn btn-pequeno">Editar</a>
                <a href="libro_eliminar.php?id=<?= $l['id'] ?>" class="btn btn-pequeno btn-eliminar"
                   onclick="return confirm('¿Eliminar este libro?');">Eliminar</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="tarjeta">
    <p>No se encontraron libros<?= $busqueda !== '' ? ' para "' . htmlspecialchars($busqueda) . '"' : '' ?>.</p>
</div>
<?php endif; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>