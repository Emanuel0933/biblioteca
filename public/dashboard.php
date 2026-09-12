<?php
declare(strict_types=1);
require __DIR__ . '/../includes/auth.php';
requerirLogin();
require __DIR__ . '/../config/db.php';

$totales = [
    'usuarios'   => (int) $pdo->query('SELECT COUNT(*) c FROM usuarios')->fetch()['c'],
    'autores'    => (int) $pdo->query('SELECT COUNT(*) c FROM autores')->fetch()['c'],
    'editoriales'=> (int) $pdo->query('SELECT COUNT(*) c FROM editoriales')->fetch()['c'],
    'categorias' => (int) $pdo->query('SELECT COUNT(*) c FROM categorias')->fetch()['c'],
    'libros'     => (int) $pdo->query('SELECT COUNT(*) c FROM libros')->fetch()['c'],
    'prestamos'  => (int) $pdo->query('SELECT COUNT(*) c FROM prestamos')->fetch()['c'],
];

$titulo = 'Panel principal';
require __DIR__ . '/../includes/header.php';
?>
<div class="tarjeta">
    <h1>Bienvenido(a), <?= htmlspecialchars($_SESSION['nombre']) ?></h1>
    <p>Este es el panel principal del Sistema de Biblioteca. Desde aquí puedes navegar a cada módulo.</p>
</div>

<div class="tarjeta">
    <h2>Resumen general</h2>
    <table>
        <tr><th>Tabla</th><th>Registros</th></tr>
        <tr><td>Usuarios</td><td><?= $totales['usuarios'] ?></td></tr>
        <tr><td>Autores</td><td><?= $totales['autores'] ?></td></tr>
        <tr><td>Editoriales</td><td><?= $totales['editoriales'] ?></td></tr>
        <tr><td>Categorías</td><td><?= $totales['categorias'] ?></td></tr>
        <tr><td>Libros</td><td><?= $totales['libros'] ?></td></tr>
        <tr><td>Préstamos</td><td><?= $totales['prestamos'] ?></td></tr>
    </table>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
