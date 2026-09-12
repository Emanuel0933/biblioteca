<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function estaAutenticado(): bool
{
    return isset($_SESSION['usuario_id']);
}

function requerirLogin(): void
{
    if (!estaAutenticado()) {
        header('Location: login.php');
        exit;
    }
}

function esAdmin(): bool
{
    return ($_SESSION['rol'] ?? '') === 'admin';
}

function requerirAdmin(): void
{
    requerirLogin();
    if (!esAdmin()) {
        http_response_code(403);
        die('Acceso restringido: se requiere rol de administrador.');
    }
}
