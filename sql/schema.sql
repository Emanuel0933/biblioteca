-- =========================================================
-- Proyecto: Sistema de Biblioteca (PHP 8 + MySQL)
-- 6 tablas, 15 registros por tabla (mínimo)
-- =========================================================

CREATE DATABASE IF NOT EXISTS biblioteca CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE biblioteca;

-- ---------------------------------------------------------
-- Tabla 1: usuarios (login del sistema)
-- ---------------------------------------------------------
DROP TABLE IF EXISTS prestamos;
DROP TABLE IF EXISTS libros;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS editoriales;
DROP TABLE IF EXISTS autores;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin','usuario') NOT NULL DEFAULT 'usuario',
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- NOTA IMPORTANTE: los 15 usuarios de ejemplo (con contraseñas ya encriptadas
-- correctamente con password_hash de PHP) se insertan con el script
-- sql/seed_usuarios.php, no aquí, para garantizar que los hashes sean válidos.
-- Ejecuta primero este schema.sql y luego corre seed_usuarios.php una vez.

-- ---------------------------------------------------------
-- Tabla 2: autores
-- ---------------------------------------------------------
CREATE TABLE autores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    nacionalidad VARCHAR(60) NOT NULL,
    fecha_nacimiento DATE NOT NULL
);

INSERT INTO autores (nombre, nacionalidad, fecha_nacimiento) VALUES
('Gabriel García Márquez', 'Colombiana', '1927-03-06'),
('Isabel Allende',         'Chilena',    '1942-08-02'),
('Jorge Luis Borges',      'Argentina',  '1899-08-24'),
('Julio Cortázar',         'Argentina',  '1914-08-26'),
('Mario Vargas Llosa',     'Peruana',    '1936-03-28'),
('Octavio Paz',            'Mexicana',   '1914-03-31'),
('Pablo Neruda',           'Chilena',    '1904-07-12'),
('Laura Esquivel',         'Mexicana',   '1950-09-30'),
('Carlos Fuentes',         'Mexicana',   '1928-11-11'),
('Elena Poniatowska',      'Mexicana',   '1932-05-19'),
('Rosario Castellanos',    'Mexicana',   '1925-05-25'),
('Juan Rulfo',             'Mexicana',   '1917-05-16'),
('Ernesto Sabato',         'Argentina',  '1911-06-24'),
('Alejo Carpentier',       'Cubana',     '1904-12-26'),
('José Saramago',          'Portuguesa', '1922-11-16');

-- ---------------------------------------------------------
-- Tabla 3: editoriales
-- ---------------------------------------------------------
CREATE TABLE editoriales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    pais VARCHAR(60) NOT NULL,
    anio_fundacion INT NOT NULL
);

INSERT INTO editoriales (nombre, pais, anio_fundacion) VALUES
('Editorial Planeta',      'España',     1949),
('Alfaguara',               'España',     1964),
('Fondo de Cultura Económica','México',   1934),
('Editorial Sudamericana',  'Argentina',  1939),
('Tusquets Editores',       'España',     1969),
('Ediciones Era',           'México',     1960),
('Seix Barral',             'España',     1911),
('Editorial Anagrama',      'España',     1969),
('Editorial Norma',         'Colombia',   1960),
('Editorial Porrúa',        'México',     1900),
('Random House Mondadori',  'España',     2001),
('Editorial Losada',        'Argentina',  1938),
('Emecé Editores',          'Argentina',  1939),
('Grijalbo',                'México',     1949),
('Ediciones Cátedra',       'España',     1976);

-- ---------------------------------------------------------
-- Tabla 4: categorias
-- ---------------------------------------------------------
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    descripcion VARCHAR(255) DEFAULT NULL
);

INSERT INTO categorias (nombre, descripcion) VALUES
('Novela',            'Obras narrativas extensas de ficción'),
('Cuento',            'Relatos breves de ficción'),
('Poesía',            'Obras en verso'),
('Ensayo',            'Textos reflexivos y argumentativos'),
('Realismo mágico',   'Ficción con elementos fantásticos naturalizados'),
('Ciencia ficción',   'Narrativa especulativa basada en ciencia y tecnología'),
('Historia',          'Obras sobre hechos históricos'),
('Biografía',         'Relatos de vida de personas reales'),
('Teatro',            'Obras escritas para representación escénica'),
('Filosofía',         'Textos de pensamiento y reflexión filosófica'),
('Infantil',          'Literatura dirigida a público infantil'),
('Juvenil',           'Literatura dirigida a público joven'),
('Policiaco',         'Narrativa de misterio y crimen'),
('Fantasía',          'Narrativa de mundos y elementos imaginarios'),
('Crónica',           'Relatos periodísticos o narrativos de hechos reales');

-- ---------------------------------------------------------
-- Tabla 5: libros (relacionada con autores, categorías y editoriales)
-- ---------------------------------------------------------
CREATE TABLE libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor_id INT NOT NULL,
    categoria_id INT NOT NULL,
    editorial_id INT NOT NULL,
    anio_publicacion INT NOT NULL,
    isbn VARCHAR(20) NOT NULL UNIQUE,
    stock INT NOT NULL DEFAULT 1,
    FOREIGN KEY (autor_id) REFERENCES autores(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE,
    FOREIGN KEY (editorial_id) REFERENCES editoriales(id) ON DELETE CASCADE
);

INSERT INTO libros (titulo, autor_id, categoria_id, editorial_id, anio_publicacion, isbn, stock) VALUES
('Cien años de soledad',        1, 5, 1,  1967, '978-0307474728', 5),
('El amor en los tiempos del cólera', 1, 1, 1, 1985, '978-0307389732', 3),
('La casa de los espíritus',    2, 5, 1,  1982, '978-1501117015', 4),
('Ficciones',                    3, 2, 7,  1944, '978-0802130303', 6),
('El Aleph',                     3, 2, 7,  1949, '978-0307950915', 2),
('Rayuela',                      4, 1, 4,  1963, '978-8437604572', 5),
('La ciudad y los perros',       5, 1, 2,  1963, '978-8420471839', 3),
('El laberinto de la soledad',   6, 4, 3,  1950, '978-9681602665', 4),
('Veinte poemas de amor y una canción desesperada', 7, 3, 12, 1924, '978-8437604435', 7),
('Como agua para chocolate',     8, 1, 14, 1989, '978-0385420174', 5),
('La región más transparente',   9, 1, 3,  1958, '978-9681601644', 2),
('La noche de Tlatelolco',      10, 15, 6, 1971, '978-9684116387', 3),
('Balún Canán',                 11, 1, 3,  1957, '978-9681601842', 2),
('Pedro Páramo',                12, 5, 3,  1955, '978-0802133908', 6),
('Sobre héroes y tumbas',       13, 1, 12, 1961, '978-9500420212', 4);

-- ---------------------------------------------------------
-- Tabla 6: prestamos (relacionada con libros y usuarios)
-- ---------------------------------------------------------
CREATE TABLE prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libro_id INT NOT NULL,
    usuario_id INT NOT NULL,
    fecha_prestamo DATE NOT NULL,
    fecha_devolucion_esperada DATE NOT NULL,
    fecha_devolucion_real DATE DEFAULT NULL,
    estado ENUM('prestado','devuelto','atrasado') NOT NULL DEFAULT 'prestado',
    FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Los 15 registros de "prestamos" se insertan desde sql/seed_usuarios.php,
-- porque dependen de que la tabla "usuarios" ya tenga datos (llave foránea).
