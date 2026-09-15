<?php
declare(strict_types=1);
require __DIR__ . '/../includes/auth.php';
requerirLogin();
require __DIR__ . '/../config/db.php';

$totalLibros      = (int) $pdo->query('SELECT COUNT(*) c FROM libros')->fetch()['c'];
$totalCategorias  = (int) $pdo->query('SELECT COUNT(*) c FROM categorias')->fetch()['c'];
$totalAutores     = (int) $pdo->query('SELECT COUNT(*) c FROM autores')->fetch()['c'];
$totalDisponibles = (int) $pdo->query('SELECT COALESCE(SUM(stock),0) c FROM libros')->fetch()['c'];

$categorias = $pdo->query('SELECT nombre FROM categorias ORDER BY nombre')->fetchAll();

$titulo = 'Inicio';
require __DIR__ . '/../includes/header.php';
?>
<div class="hero">
    <h1>📚 Bienvenido(a) a la Biblioteca, <?= htmlspecialchars($_SESSION['nombre']) ?></h1>
    <p class="hero-texto">
        Un espacio para descubrir, tomar prestado y disfrutar de nuestra colección de libros.
        Explora el catálogo, revisa la disponibilidad y encuentra tu próxima lectura.
    </p>
    <a href="libros.php" class="btn btn-grande">📖 Ver catálogo de libros</a>
</div>

<div class="inicio-stats">
    <div class="stat-card">
        <span class="stat-numero"><?= $totalLibros ?></span>
        <span class="stat-etiqueta">Libros en catálogo</span>
    </div>
    <div class="stat-card">
        <span class="stat-numero"><?= $totalDisponibles ?></span>
        <span class="stat-etiqueta">Ejemplares disponibles</span>
    </div>
    <div class="stat-card">
        <span class="stat-numero"><?= $totalAutores ?></span>
        <span class="stat-etiqueta">Autores</span>
    </div>
    <div class="stat-card">
        <span class="stat-numero"><?= $totalCategorias ?></span>
        <span class="stat-etiqueta">Categorías</span>
    </div>
</div>

<div class="tarjeta">
    <h2>Explora por categoría</h2>
    <div class="chips">
        <?php foreach ($categorias as $c): ?>
            <a class="chip" href="libros.php?categoria=<?= urlencode($c['nombre']) ?>"><?= htmlspecialchars($c['nombre']) ?></a>        <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>