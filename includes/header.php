<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Biblioteca') ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="topbar">
    <a href="dashboard.php" class="brand">📚 Biblioteca</a>
    <?php if (estaAutenticado()): ?>
        <nav>
            <a href="libros.php">Libros</a>
            <a href="prestamos.php">Préstamos</a>
            <a href="categorias.php">Categorías</a>
            <?php if (esAdmin()): ?><a href="usuarios.php">Usuarios</a><?php endif; ?>
            <span class="usuario-actual">Hola, <?= htmlspecialchars($_SESSION['nombre'] ?? '') ?></span>
            <a href="logout.php" class="salir">Salir</a>
        </nav>
    <?php endif; ?>
</header>
<main class="contenedor">
