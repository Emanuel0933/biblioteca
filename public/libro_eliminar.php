<?php
declare(strict_types=1);
require __DIR__ . '/../includes/auth.php';
requerirLogin();
require __DIR__ . '/../config/db.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id) {
    $stmt = $pdo->prepare('DELETE FROM libros WHERE id = ?');
    $stmt->execute([$id]);
}

header('Location: libros.php');
exit;
