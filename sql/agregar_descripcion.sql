-- Agrega la columna de descripción a libros (solo si no existe ya)
ALTER TABLE libros ADD COLUMN descripcion TEXT NULL AFTER isbn;

-- Descripciones para los libros de ejemplo ya existentes
UPDATE libros SET descripcion = 'Historia de la familia Buendía a lo largo de siete generaciones en el pueblo ficticio de Macondo.' WHERE titulo = 'Cien años de soledad';
UPDATE libros SET descripcion = 'Una historia de amor que perdura más de cincuenta años entre Florentino Ariza y Fermina Daza.' WHERE titulo = 'El amor en los tiempos del cólera';
UPDATE libros SET descripcion = 'Saga familiar que sigue a los Trueba a través de varias generaciones en un país latinoamericano no identificado.' WHERE titulo = 'La casa de los espíritus';
UPDATE libros SET descripcion = 'Colección de relatos que exploran laberintos, espejos y realidades alternas.' WHERE titulo = 'Ficciones';
UPDATE libros SET descripcion = 'Colección de cuentos que profundiza en temas de infinito, identidad y destino.' WHERE titulo = 'El Aleph';
UPDATE libros SET descripcion = 'Novela experimental que puede leerse en distinto orden, ambientada en París y Buenos Aires.' WHERE titulo = 'Rayuela';
UPDATE libros SET descripcion = 'Relata la vida de cadetes en un colegio militar de Lima y las tensiones entre ellos.' WHERE titulo = 'La ciudad y los perros';
UPDATE libros SET descripcion = 'Ensayo sobre la identidad y la soledad del mexicano a través de la historia y la cultura.' WHERE titulo = 'El laberinto de la soledad';
UPDATE libros SET descripcion = 'Colección de poemas líricos sobre el amor, el deseo y la melancolía.' WHERE titulo = 'Veinte poemas de amor y una canción desesperada';
UPDATE libros SET descripcion = 'Historia de Tita, quien expresa sus emociones a través de la cocina en el México revolucionario.' WHERE titulo = 'Como agua para chocolate';
UPDATE libros SET descripcion = 'Retrato de la vida y la sociedad en la Ciudad de México a mediados del siglo XX.' WHERE titulo = 'La región más transparente';
UPDATE libros SET descripcion = 'Crónica testimonial sobre los sucesos y la represión estudiantil de 1968 en México.' WHERE titulo = 'La noche de Tlatelolco';
UPDATE libros SET descripcion = 'Historia de una joven que regresa a su pueblo natal en Chiapas y enfrenta las tensiones sociales de la región.' WHERE titulo = 'Balún Canán';
UPDATE libros SET descripcion = 'Juan Preciado viaja a Comala en busca de su padre y descubre un pueblo habitado por fantasmas.' WHERE titulo = 'Pedro Páramo';
UPDATE libros SET descripcion = 'Explora la culpa, la locura y la búsqueda de sentido a través de varios personajes en Buenos Aires.' WHERE titulo = 'Sobre héroes y tumbas';

-- Nuevo autor, editorial y libro: Harry Potter
INSERT INTO autores (nombre, nacionalidad, fecha_nacimiento) VALUES
('J. K. Rowling', 'Británica', '1965-07-31');

INSERT INTO editoriales (nombre, pais, anio_fundacion) VALUES
('Bloomsbury Publishing', 'Reino Unido', 1986);

INSERT INTO libros (titulo, autor_id, categoria_id, editorial_id, anio_publicacion, isbn, descripcion, stock) VALUES
(
    'Harry Potter and the Philosopher''s Stone',
    (SELECT id FROM autores WHERE nombre = 'J. K. Rowling'),
    (SELECT id FROM categorias WHERE nombre = 'Fantasía'),
    (SELECT id FROM editoriales WHERE nombre = 'Bloomsbury Publishing'),
    1997,
    '978-0747532699',
    'Un niño huérfano descubre en su undécimo cumpleaños que es un mago y es invitado a estudiar en el Colegio Hogwarts de Magia y Hechicería.',
    6
);