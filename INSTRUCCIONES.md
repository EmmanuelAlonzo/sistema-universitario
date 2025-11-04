# Sistema Universitario - Arquitectura MVC

Sistema de gestión académica universitaria desarrollado con **PHP, MySQL y JavaScript (AJAX)** siguiendo el patrón **Modelo-Vista-Controlador (MVC)**.

## 📋 Características Principales

✅ **Estructura MVC** - Separación clara de responsabilidades  
✅ **Funcionalidad CRUD Completa** - Create, Read, Update, Delete  
✅ **Autenticación y Seguridad** - Sesiones PHP y contraseñas hasheadas  
✅ **Integración AJAX** - Operaciones dinámicas sin recargar página  
✅ **Claridad en el Funcionamiento** - Código documentado y organizado

## 🏗️ Estructura del Proyecto

```
Proyecto final/
│
├── config/
│   └── database.php          # Configuración de base de datos
│
├── models/                    # CAPA: MODELO
│   ├── Usuario.php           # Modelo de usuarios (empleados)
│   ├── Estudiante.php        # Modelo de estudiantes
│   └── Materia.php           # Modelo de materias
│
├── controllers/               # CAPA: CONTROLADOR
│   ├── AuthController.php    # Controlador de autenticación
│   └── EstudianteController.php  # Controlador de estudiantes
│
├── views/                     # CAPA: VISTA
│   ├── login.php             # Vista de login
│   ├── dashboard.php         # Vista del dashboard principal
│   └── estudiantes.php       # Vista de gestión de estudiantes
│
├── assets/
│   └── js/
│       ├── auth.js           # JavaScript para autenticación (AJAX)
│       └── estudiantes.js    # JavaScript para gestión de estudiantes (AJAX)
│
├── database/
│   └── schema.sql            # Script de creación de base de datos
│
└── index.php                 # Punto de entrada
```

## 🚀 Instalación

### 1. Requisitos Previos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx) o XAMPP/WAMP

### 2. Configuración de la Base de Datos

```bash
# 1. Crear la base de datos
mysql -u root -p < database/schema.sql

# 2. Ajustar credenciales en config/database.php si es necesario
```

### 3. Configuración del Proyecto

```php
// config/database.php
private $host = "localhost";
private $db_name = "sistema_universitario";
private $username = "root";
private $password = "";  // Ajustar según tu configuración
```

### 4. Ejecutar el Proyecto

- Colocar el proyecto en la carpeta del servidor web (htdocs para XAMPP)
- Acceder a: `http://localhost/Proyecto final/`

## 👥 Usuarios de Prueba

### Administrador
- **Usuario:** admin  
- **Contraseña:** admin123

### Coordinador
- **Usuario:** coord.sistemas  
- **Contraseña:** coord123

### Maestro
- **Usuario:** prof.martinez  
- **Contraseña:** prof123

### Secretaria
- **Usuario:** sec.garcia  
- **Contraseña:** sec123

### Estudiante
- **Usuario:** juan.perez  
- **Contraseña:** est123

## 📊 Arquitectura MVC

### Modelo (Model)
- Maneja la lógica de datos y acceso a la base de datos
- Archivos: `models/*.php`
- Responsabilidad: Operaciones CRUD, validaciones de datos

### Vista (View)
- Presenta la información al usuario
- Archivos: `views/*.php`
- Responsabilidad: Interfaz de usuario, formularios

### Controlador (Controller)
- Procesa las peticiones del usuario
- Archivos: `controllers/*.php`
- Responsabilidad: Lógica de negocio, respuestas AJAX

## 🔐 Seguridad Implementada

1. **Autenticación basada en sesiones PHP**
2. **Contraseñas hasheadas** con `password_hash()` y `password_verify()`
3. **Validación y sanitización** de datos con `htmlspecialchars()` y `strip_tags()`
4. **Prepared Statements** en PDO para prevenir SQL Injection
5. **Control de permisos** según rol de usuario

## 💾 Funcionalidad CRUD

### Estudiantes (Ejemplo)

**CREATE**
```php
POST /controllers/EstudianteController.php
action=create&nombre=...&email=...&carrera=...
```

**READ**
```php
GET /controllers/EstudianteController.php?action=read
```

**UPDATE**
```php
POST /controllers/EstudianteController.php
action=update&id=...&nombre=...&email=...
```

**DELETE**
```php
POST /controllers/EstudianteController.php
action=delete&id=...
```

## ⚡ Integración AJAX

Todas las operaciones CRUD se realizan mediante AJAX para una mejor experiencia de usuario:

```javascript
// Ejemplo: Crear estudiante
fetch('../controllers/EstudianteController.php', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => {
    // Procesar respuesta sin recargar página
});
```

## 📝 Base de Datos

### Tablas Principales

- **usuarios** - Empleados del sistema (admin, coordinador, maestro, etc.)
- **estudiantes** - Estudiantes registrados
- **materias** - Materias/cursos disponibles
- **inscripciones** - Relación estudiante-materia
- **tareas** - Asignaciones de tareas
- **mensajes** - Sistema de mensajería
- **eventos** - Eventos académicos
- **pagos** - Control de pagos

## 🎯 Características por Rol

### Administrador
- Acceso completo al sistema
- Gestión de usuarios y estudiantes
- Configuración del sistema

### Coordinador
- Gestión de estudiantes y maestros
- Asignación de materias
- Visualización de reportes

### Maestro
- Gestión de sus materias
- Calificaciones de estudiantes
- Creación de tareas

### Estudiante
- Ver materias inscritas
- Entregar tareas
- Consultar calificaciones

### Secretaria
- Registro de estudiantes
- Gestión de documentos
- Inscripciones

## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP 7.4+
- **Base de Datos:** MySQL 5.7+
- **Frontend:** HTML5, TailwindCSS
- **JavaScript:** Vanilla JS con Fetch API (AJAX)
- **Patrón:** MVC (Modelo-Vista-Controlador)

## 📖 Documentación del Código

Cada archivo incluye comentarios detallados explicando:
- Propósito del archivo
- Funciones y métodos
- Parámetros y retornos
- Flujo de datos

## 🔄 Flujo de Funcionamiento

1. Usuario accede a `index.php` → Redirige a `login.php`
2. Usuario ingresa credenciales → `AuthController.php` valida
3. Si es válido → Crea sesión y redirige a `dashboard.php`
4. Usuario realiza operación CRUD → JavaScript envía petición AJAX
5. Controlador procesa → Modelo ejecuta en BD → Respuesta JSON
6. JavaScript actualiza interfaz sin recargar página

## 📄 Licencia

Este proyecto es un sistema educativo de demostración.

## 👨‍💻 Autor

Sistema desarrollado como proyecto universitario.

---

**Nota:** Este es un sistema de demostración. Para producción, implementar medidas adicionales de seguridad.
