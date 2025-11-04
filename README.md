# Sistema Universitario - Arquitectura MVC

Sistema de gestión académica universitaria desarrollado con **PHP, MySQL y JavaScript (AJAX)** siguiendo el patrón **Modelo-Vista-Controlador (MVC)**.

## ✨ Características del Proyecto

✅ **Estructura MVC** - Separación clara de Modelo, Vista y Controlador  
✅ **Funcionalidad CRUD Completa** - Create, Read, Update, Delete en todas las entidades  
✅ **Autenticación y Seguridad** - Sesiones PHP con contraseñas hasheadas (password_hash)  
✅ **Integración JavaScript/AJAX** - Operaciones dinámicas sin recargar la página  
✅ **Claridad en el Funcionamiento** - Código documentado y organizado

## 🏗️ Arquitectura MVC

```
Modelo (models/)     → Lógica de datos y acceso a BD
Vista (views/)       → Interfaz de usuario (HTML/PHP)
Controlador (controllers/) → Lógica de negocio y procesamiento AJAX
```

## 🚀 Instalación Rápida

### Requisitos
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx (o XAMPP/WAMP)

### Pasos

1. **Clonar el repositorio**
```bash
git clone https://github.com/EmmanuelAlonzo/sistema-universitario.git
```

2. **Crear la base de datos**
```bash
mysql -u root -p < database/schema.sql
```

3. **Configurar credenciales**
Editar `config/database.php` con tus datos de MySQL

4. **Acceder al sistema**
Abrir en navegador: `http://localhost/Proyecto final/`

## 👥 Usuarios de Prueba

| Rol | Usuario | Contraseña |
|-----|---------|------------|
| 🔧 Administrador | `admin` | `admin123` |
| 👨‍💼 Coordinador | `coord.sistemas` | `coord123` |
| 👨‍🏫 Maestro | `prof.martinez` | `prof123` |
| 📋 Secretaria | `sec.garcia` | `sec123` |
| 🎓 Estudiante | `juan.perez` | `est123` |

## 🛠️ Tecnologías

- **Backend:** PHP 7.4+ (PDO para BD)
- **Base de Datos:** MySQL 5.7+
- **Frontend:** HTML5 + TailwindCSS
- **JavaScript:** Vanilla JS con Fetch API (AJAX)
- **Arquitectura:** MVC (Modelo-Vista-Controlador)
- **Seguridad:** Sessions, Password Hashing, Prepared Statements

## 📊 Funcionalidad CRUD

Cada entidad (Estudiantes, Usuarios, Materias) cuenta con:

- **CREATE** - Crear nuevos registros con validación
- **READ** - Listar y buscar registros
- **UPDATE** - Editar registros existentes
- **DELETE** - Eliminar registros con confirmación

Todas las operaciones se realizan vía **AJAX** para mejor experiencia de usuario.

## 📁 Estructura del Proyecto

```
config/          # Configuración de BD
models/          # Modelos (Usuario, Estudiante, Materia)
controllers/     # Controladores con lógica de negocio
views/           # Vistas (Login, Dashboard, Gestión)
assets/js/       # JavaScript con AJAX
database/        # Scripts SQL
```

## 📖 Documentación Completa

Ver [INSTRUCCIONES.md](INSTRUCCIONES.md) para documentación detallada del proyecto.

## 🔐 Seguridad

- Autenticación basada en sesiones PHP
- Contraseñas hasheadas con `password_hash()`
- Prepared Statements (PDO) contra SQL Injection
- Validación y sanitización de datos
- Control de permisos por rol

## 📝 Versión Anterior

La versión HTML/JavaScript original se encuentra en: `Poyecto Final.html`

## 👨‍💻 Desarrollo

Proyecto desarrollado como sistema académico universitario siguiendo las mejores prácticas de desarrollo web.
