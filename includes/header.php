<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Biblioteca') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <a href="inicio.php" class="brand">
        <svg width="36" height="36" viewBox="0 0 64 64" class="logo-svg">
            <defs>
                <linearGradient id="gradoLogo" x1="0" y1="0" x2="64" y2="64">
                    <stop offset="0" stop-color="#3f6fb5"/>
                    <stop offset="1" stop-color="#6a3fae"/>
                </linearGradient>
            </defs>
            <rect width="64" height="64" rx="16" fill="url(#gradoLogo)"/>
            <path d="M32 22 C26 18 17 17 13 19 L13 45 C17 43 26 44 32 48 C38 44 47 43 51 45 L51 19 C47 17 38 18 32 22 Z" fill="#fff"/>
            <path d="M32 22 L32 48" stroke="#c9d6ea" stroke-width="1.5"/>
            <path d="M17 24 C21 23 26 23 30 26" stroke="#c9d6ea" stroke-width="1.3" fill="none" stroke-linecap="round"/>
            <path d="M17 30 C21 29 26 29 30 32" stroke="#c9d6ea" stroke-width="1.3" fill="none" stroke-linecap="round"/>
            <path d="M17 36 C21 35 26 35 30 38" stroke="#c9d6ea" stroke-width="1.3" fill="none" stroke-linecap="round"/>
            <path d="M34 26 C38 23 43 23 47 24" stroke="#c9d6ea" stroke-width="1.3" fill="none" stroke-linecap="round"/>
            <path d="M34 32 C38 29 43 29 47 30" stroke="#c9d6ea" stroke-width="1.3" fill="none" stroke-linecap="round"/>
            <path d="M34 38 C38 35 43 35 47 36" stroke="#c9d6ea" stroke-width="1.3" fill="none" stroke-linecap="round"/>
            <rect x="29" y="12" width="6" height="17" fill="#f2b134"/>
            <path d="M29 29 L35 29 L32 33 Z" fill="#c9902b"/>
        </svg>
        <span>Biblioteca</span>
    </a>
    <?php if (estaAutenticado()): ?>
        <nav>
            <a href="inicio.php">Inicio</a>
            <a href="libros.php">Libros</a>
            <?php if (esAdmin()): ?>
                <a href="prestamos.php">Préstamos</a>
                <a href="categorias.php">Categorías</a>
                <a href="usuarios.php">Usuarios</a>
            <?php endif; ?>
            <span class="usuario-actual">Hola, <?= htmlspecialchars($_SESSION['nombre'] ?? '') ?></span>
            <span class="reloj" id="reloj"></span>
            <a href="logout.php" class="salir">Salir</a>
        </nav>
    <?php endif; ?>
</header>
<main class="contenedor">