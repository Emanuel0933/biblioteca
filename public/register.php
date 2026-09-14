<?php
declare(strict_types=1);
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../config/db.php';

if (estaAutenticado()) {
    header('Location: inicio.php');
    exit;
}

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($nombre === '' || $email === '' || $password === '') {
        $error = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo electrónico no es válido.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($password !== $password2) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Ese correo ya está registrado.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare(
                'INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([$nombre, $email, $hash, 'usuario']);
            $exito = 'Cuenta creada correctamente. Ya puedes iniciar sesión.';
        }
    }
}

$titulo = 'Crear cuenta';
require __DIR__ . '/../includes/header.php';
?>
<div class="tarjeta form-login">
    <h1>Crear cuenta</h1>

    <?php if ($error): ?><div class="alerta-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($exito): ?><div class="alerta-exito"><?= htmlspecialchars($exito) ?> <a href="login.php">Iniciar sesión</a></div><?php endif; ?>

    <?php if (!$exito): ?>
    <form method="post" action="register.php">
        <label for="nombre">Nombre completo</label>
        <input type="text" id="nombre" name="nombre" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">

        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required minlength="6">

        <label for="password2">Confirmar contraseña</label>
        <input type="password" id="password2" name="password2" required minlength="6">

        <button type="submit">Registrarme</button>
    </form>
    <?php endif; ?>

    <p style="margin-top:16px;">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
