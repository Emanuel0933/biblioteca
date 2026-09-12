<?php
declare(strict_types=1);

/**
 * Ejecuta este script UNA sola vez después de importar schema.sql,
 * ya sea desde el navegador (http://tu-sitio/sql/seed_usuarios.php)
 * o por línea de comandos: php sql/seed_usuarios.php
 *
 * Inserta los 15 registros de "usuarios" con contraseñas correctamente
 * encriptadas (password_hash) y los 15 registros de "prestamos".
 */

require __DIR__ . '/../config/db.php';

$usuarios = [
    ['Ana Torres',      'ana.torres@correo.com',      'admin'],
    ['Luis Pérez',      'luis.perez@correo.com',      'usuario'],
    ['María Gómez',     'maria.gomez@correo.com',     'usuario'],
    ['Carlos Ruiz',     'carlos.ruiz@correo.com',     'usuario'],
    ['Diana López',     'diana.lopez@correo.com',     'usuario'],
    ['Jorge Medina',    'jorge.medina@correo.com',    'usuario'],
    ['Paola Sánchez',   'paola.sanchez@correo.com',   'usuario'],
    ['Ricardo Fuentes', 'ricardo.fuentes@correo.com', 'usuario'],
    ['Sofía Ramírez',   'sofia.ramirez@correo.com',   'usuario'],
    ['Andrés Castillo', 'andres.castillo@correo.com', 'usuario'],
    ['Valeria Ortiz',   'valeria.ortiz@correo.com',   'usuario'],
    ['Fernando Rojas',  'fernando.rojas@correo.com',  'usuario'],
    ['Camila Vargas',   'camila.vargas@correo.com',   'usuario'],
    ['Sebastián Rivas', 'sebastian.rivas@correo.com', 'usuario'],
    ['Elena Molina',    'elena.molina@correo.com',    'usuario'],
];

// Contraseña de prueba para TODOS los usuarios de ejemplo: 123456
$passwordPlano = '123456';
$hash = password_hash($passwordPlano, PASSWORD_BCRYPT);

$stmtUsuario = $pdo->prepare(
    'INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)'
);

$pdo->beginTransaction();

try {
    foreach ($usuarios as [$nombre, $email, $rol]) {
        $stmtUsuario->execute([$nombre, $email, $hash, $rol]);
    }

    $prestamos = [
        [1,  2,  '2026-08-01', '2026-08-15', '2026-08-14', 'devuelto'],
        [2,  3,  '2026-08-02', '2026-08-16', null,          'prestado'],
        [3,  4,  '2026-08-03', '2026-08-17', '2026-08-20', 'devuelto'],
        [4,  5,  '2026-08-04', '2026-08-18', null,          'atrasado'],
        [5,  6,  '2026-08-05', '2026-08-19', '2026-08-18', 'devuelto'],
        [6,  7,  '2026-08-06', '2026-08-20', null,          'prestado'],
        [7,  8,  '2026-08-07', '2026-08-21', '2026-08-21', 'devuelto'],
        [8,  9,  '2026-08-08', '2026-08-22', null,          'prestado'],
        [9,  10, '2026-08-09', '2026-08-23', '2026-08-25', 'devuelto'],
        [10, 11, '2026-08-10', '2026-08-24', null,          'atrasado'],
        [11, 12, '2026-08-11', '2026-08-25', '2026-08-24', 'devuelto'],
        [12, 13, '2026-08-12', '2026-08-26', null,          'prestado'],
        [13, 14, '2026-08-13', '2026-08-27', '2026-08-27', 'devuelto'],
        [14, 15, '2026-08-14', '2026-08-28', null,          'prestado'],
        [15, 2,  '2026-08-15', '2026-08-29', '2026-08-28', 'devuelto'],
    ];

    $stmtPrestamo = $pdo->prepare(
        'INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, fecha_devolucion_esperada, fecha_devolucion_real, estado)
         VALUES (?, ?, ?, ?, ?, ?)'
    );

    foreach ($prestamos as $p) {
        $stmtPrestamo->execute($p);
    }

    $pdo->commit();
    echo "Listo: se insertaron 15 usuarios y 15 prestamos.\n";
    echo "Puedes iniciar sesion con cualquier correo de la lista y la contrasena: {$passwordPlano}\n";
} catch (Throwable $e) {
    $pdo->rollBack();
    echo 'Error al sembrar datos: ' . $e->getMessage() . "\n";
}
