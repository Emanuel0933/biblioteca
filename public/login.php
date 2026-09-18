<?php
declare(strict_types=1);
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../config/db.php';

if (estaAutenticado()) {
    header('Location: inicio.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Debes completar correo y contraseña.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre']     = $usuario['nombre'];
            $_SESSION['rol']        = $usuario['rol'];
            header('Location: inicio.php');
            exit;
        }
        $error = 'Correo o contraseña incorrectos.';
    }
}

$titulo = 'Iniciar sesión';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="fondo-auth">
<div class="auth-split">
    <div class="auth-panel-visual">
        <div class="auth-brillo-1"></div>
        <div class="auth-brillo-2"></div>
        <div class="auth-panel-contenido">
            <svg width="56" height="56" viewBox="0 0 64 64" class="logo-svg">
                <defs>
                    <linearGradient id="gradoLogoAuth" x1="0" y1="0" x2="64" y2="64">
                        <stop offset="0" stop-color="#3f6fb5"/>
                        <stop offset="1" stop-color="#9c6ade"/>
                    </linearGradient>
                </defs>
                <rect width="64" height="64" rx="16" fill="url(#gradoLogoAuth)"/>
                <path d="M32 22 C26 18 17 17 13 19 L13 45 C17 43 26 44 32 48 C38 44 47 43 51 45 L51 19 C47 17 38 18 32 22 Z" fill="#fff"/>
                <path d="M32 22 L32 48" stroke="#c9d6ea" stroke-width="1.5"/>
                <rect x="29" y="12" width="6" height="17" fill="#f2b134"/>
                <path d="M29 29 L35 29 L32 33 Z" fill="#c9902b"/>
            </svg>
            <h1>Sistema de Biblioteca</h1>
            <p>Explora el catálogo, revisa disponibilidad y gestiona préstamos, todo en un mismo lugar.</p>
            <ul class="auth-lista">
                <li>📚 Catálogo completo con búsqueda</li>
                <li>🔄 Seguimiento de préstamos</li>
                <li>🏷️ Organizado por categorías</li>
            </ul>
        </div>
    </div>

    <div class="auth-panel-form">
        <div class="auth-card">
            <h2>Bienvenido de vuelta</h2>
            <p class="auth-subtitulo">Inicia sesión para continuar</p>

            <?php if ($error): ?>
                <div class="alerta-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" action="login.php" class="auth-form">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" required autofocus placeholder="tucorreo@ejemplo.com">

                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">

                <button type="submit">Entrar</button>
            </form>

            <p class="auth-alterno">¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
        </div>
    </div>
</div>
</body>
</html>