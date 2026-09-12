# Sistema de Biblioteca — PHP 8 + MySQL

Proyecto académico: sitio web con **PHP 8**, base de datos **MySQL**, **6 tablas** con **15 registros** cada una, **login** con sesiones y contraseñas encriptadas, y **formularios** CRUD (crear, leer, actualizar, eliminar).

## 1. Tema y modelo de datos

Tema: gestión de una biblioteca.

| # | Tabla         | Descripción                                    | Relación (FK)                                  |
|---|---------------|-------------------------------------------------|-------------------------------------------------|
| 1 | `usuarios`    | Cuentas del sistema (login)                     | —                                                 |
| 2 | `autores`     | Autores de los libros                           | —                                                 |
| 3 | `editoriales` | Casas editoriales                               | —                                                 |
| 4 | `categorias`  | Géneros/categorías literarias                   | —                                                 |
| 5 | `libros`      | Catálogo de libros                              | `autor_id`, `categoria_id`, `editorial_id`       |
| 6 | `prestamos`   | Préstamos de libros a usuarios                  | `libro_id`, `usuario_id`                         |

Las 6 tablas tienen **15 registros** de ejemplo (ver `sql/schema.sql` y `sql/seed_usuarios.php`).

## 2. Estructura del proyecto

```
biblioteca-php/
├── config/
│   └── db.php              # Conexión PDO a MySQL
├── includes/
│   ├── auth.php             # Sesión, login requerido, rol admin
│   ├── header.php
│   └── footer.php
├── public/                  # Document root del sitio
│   ├── index.php
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   ├── dashboard.php
│   ├── libros.php / libro_form.php / libro_eliminar.php
│   ├── prestamos.php / prestamo_form.php / prestamo_eliminar.php
│   ├── categorias.php       # CRUD completo en una sola vista
│   └── usuarios.php         # Solo administrador
├── sql/
│   ├── schema.sql           # CREATE TABLE + 15 registros para 4 tablas
│   └── seed_usuarios.php    # Inserta 15 usuarios (password_hash) y 15 préstamos
├── css/style.css
├── .gitignore
└── README.md
```

## 3. Instalación local (XAMPP / Laragon / WAMP)

1. Copia la carpeta `biblioteca-php` dentro de tu directorio de proyectos (p. ej. `htdocs` o `www`).
2. Crea la base de datos importando el script:
   ```bash
   mysql -u root -p < sql/schema.sql
   ```
3. Ajusta `config/db.php` si tu usuario/contraseña de MySQL son distintos (o define las variables de entorno `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_PORT`).
4. Ejecuta el script de siembra **una sola vez** para crear los 15 usuarios y 15 préstamos:
   ```bash
   php sql/seed_usuarios.php
   ```
   o visita `http://localhost/biblioteca-php/sql/seed_usuarios.php` en el navegador.
5. Levanta el servidor apuntando a la carpeta `public/`:
   ```bash
   php -S localhost:2701 -t public
   ```
6. Entra a `http://localhost:2701/login.php`. Usuario de prueba: cualquier correo de `sql/seed_usuarios.php` (ej. `ana.torres@correo.com`), contraseña: `123456`.

## 4. Cambiar el puerto 2701 por el dominio local `www.mipagina.com`

Cuando corres el proyecto con `php -S localhost:2701 -t public`, tu sitio solo responde en `http://localhost:2701`. Para que responda en `http://www.mipagina.com` (sin puerto) hay que hacer **dos cosas**: decirle a tu computadora que ese nombre apunta a tu propia máquina, y decirle a tu servidor web qué carpeta debe servir para ese nombre.

### Opción A: con Apache (XAMPP/WAMP) — recomendada

1. **Agrega el dominio al archivo hosts** para que tu PC resuelva ese nombre hacia sí misma:
   - Windows: edita como administrador `C:\Windows\System32\drivers\etc\hosts`
   - Linux/Mac: edita con `sudo` el archivo `/etc/hosts`

   Agrega al final:
   ```
   127.0.0.1   www.mipagina.com
   ```

2. **Crea un Virtual Host en Apache** (archivo `httpd-vhosts.conf`, dentro de `xampp/apache/conf/extra/`):
   ```apache
   <VirtualHost *:80>
       ServerName www.mipagina.com
       DocumentRoot "C:/xampp/htdocs/biblioteca-php/public"
       <Directory "C:/xampp/htdocs/biblioteca-php/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
   (En Linux/Mac ajusta la ruta de `DocumentRoot` a donde tengas el proyecto.)

3. Verifica que en `httpd.conf` esté incluida la línea:
   ```apache
   Include conf/extra/httpd-vhosts.conf
   ```

4. Reinicia Apache. Ahora podrás entrar directamente a **http://www.mipagina.com** (puerto 80, el que usa el navegador por defecto), sin escribir `:2701`.

### Opción B: seguir usando el servidor embebido de PHP, pero con el dominio

Si prefieres mantener `php -S ...` (sin Apache), puedes redirigir el puerto 80 hacia el 2701 con un proxy simple, por ejemplo con Node (`http-proxy`) o con una regla de `iptables`/`netsh` que reenvíe el puerto 80 hacia 2701. La opción más sencilla y estándar para un proyecto PHP en local sigue siendo la **Opción A con Apache**.

> Nota: el dominio `www.mipagina.com` solo funcionará **en tu propia computadora** (por eso se edita el archivo hosts). Para que sea accesible desde internet con ese nombre real, necesitarías comprar el dominio y apuntarlo a un servidor con IP pública.

## 5. Manejo de ramas en Git (3 ramas)

Flujo sugerido de trabajo con 3 ramas:

```bash
# Inicializar el repositorio (si aún no existe)
git init
git add .
git commit -m "Estructura inicial del proyecto: PHP8 + MySQL + login + CRUD"

# Rama principal (producción / versión estable)
git branch -M main

# Rama de desarrollo (integración de features)
git checkout -b develop

# Rama de una funcionalidad específica
git checkout -b feature/login-y-registro

# ... trabajas y haces commits en feature/login-y-registro ...
git add .
git commit -m "Agrega login, registro y control de sesiones"

# Fusionar la funcionalidad terminada de vuelta a develop
git checkout develop
git merge feature/login-y-registro

# Cuando develop está estable, fusionar a main
git checkout main
git merge develop
```

### Subir el proyecto a GitHub

```bash
git remote add origin https://github.com/TU-USUARIO/biblioteca-php.git
git push -u origin main
git push -u origin develop
git push -u origin feature/login-y-registro
```

Con esto el repositorio queda en GitHub con **3 ramas**: `main` (estable), `develop` (integración) y `feature/...` (funcionalidad en progreso). Puedes repetir el patrón `feature/nombre-de-la-funcionalidad` para cada nuevo módulo (por ejemplo `feature/prestamos`, `feature/reportes`).

## 6. Notas de seguridad incluidas

- Contraseñas guardadas con `password_hash()` (bcrypt) y verificadas con `password_verify()`.
- Todas las consultas usan **sentencias preparadas (PDO)** para evitar inyección SQL.
- Salida escapada con `htmlspecialchars()` para evitar XSS.
- Rutas protegidas con `requerirLogin()` / `requerirAdmin()`.
