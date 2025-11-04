-- Script de inicialización de la base de datos
-- Sistema Universitario Completo

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS sistema_universitario CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_universitario;

-- Tabla de usuarios (empleados: admin, coordinador, maestro, secretaria, soporte)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id VARCHAR(20) UNIQUE NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    rol ENUM('administrador', 'coordinador', 'maestro', 'secretaria', 'soporte') NOT NULL,
    permisos TEXT,
    materias_asignadas TEXT,
    estado ENUM('active', 'inactive') DEFAULT 'active',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_rol (rol),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de estudiantes
CREATE TABLE IF NOT EXISTS estudiantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    carrera VARCHAR(100) NOT NULL,
    semestre INT NOT NULL,
    cursos TEXT,
    calificaciones TEXT,
    pagos TEXT,
    estado ENUM('active', 'inactive') DEFAULT 'active',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_carrera (carrera),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de materias
CREATE TABLE IF NOT EXISTS materias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    creditos INT NOT NULL,
    carrera VARCHAR(100) NOT NULL,
    semestre INT NOT NULL,
    descripcion TEXT,
    profesor_id INT,
    horario TEXT,
    aula VARCHAR(50),
    cupo_maximo INT DEFAULT 30,
    estado ENUM('active', 'inactive') DEFAULT 'active',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (profesor_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_codigo (codigo),
    INDEX idx_carrera (carrera),
    INDEX idx_profesor (profesor_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de inscripciones (relación estudiante-materia)
CREATE TABLE IF NOT EXISTS inscripciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id INT NOT NULL,
    materia_id INT NOT NULL,
    periodo VARCHAR(20) NOT NULL,
    calificacion DECIMAL(5,2),
    asistencias INT DEFAULT 0,
    estado ENUM('cursando', 'aprobado', 'reprobado', 'retirado') DEFAULT 'cursando',
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id) ON DELETE CASCADE,
    FOREIGN KEY (materia_id) REFERENCES materias(id) ON DELETE CASCADE,
    UNIQUE KEY unique_inscripcion (estudiante_id, materia_id, periodo),
    INDEX idx_estudiante (estudiante_id),
    INDEX idx_materia (materia_id),
    INDEX idx_periodo (periodo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de tareas/asignaciones
CREATE TABLE IF NOT EXISTS tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    materia_id INT NOT NULL,
    profesor_id INT NOT NULL,
    fecha_entrega DATETIME NOT NULL,
    puntos INT DEFAULT 100,
    archivo_adjunto VARCHAR(255),
    estado ENUM('active', 'inactive') DEFAULT 'active',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (materia_id) REFERENCES materias(id) ON DELETE CASCADE,
    FOREIGN KEY (profesor_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_materia (materia_id),
    INDEX idx_fecha_entrega (fecha_entrega)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de entregas de tareas
CREATE TABLE IF NOT EXISTS entregas_tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tarea_id INT NOT NULL,
    estudiante_id INT NOT NULL,
    archivo_entrega VARCHAR(255),
    comentarios TEXT,
    calificacion DECIMAL(5,2),
    fecha_entrega TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('entregado', 'calificado', 'tardio') DEFAULT 'entregado',
    FOREIGN KEY (tarea_id) REFERENCES tareas(id) ON DELETE CASCADE,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_entrega (tarea_id, estudiante_id),
    INDEX idx_tarea (tarea_id),
    INDEX idx_estudiante (estudiante_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de mensajes
CREATE TABLE IF NOT EXISTS mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    remitente_id INT NOT NULL,
    remitente_tipo ENUM('usuario', 'estudiante') NOT NULL,
    destinatario_id INT NOT NULL,
    destinatario_tipo ENUM('usuario', 'estudiante') NOT NULL,
    asunto VARCHAR(200) NOT NULL,
    mensaje TEXT NOT NULL,
    leido BOOLEAN DEFAULT FALSE,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_destinatario (destinatario_id, destinatario_tipo),
    INDEX idx_remitente (remitente_id, remitente_tipo),
    INDEX idx_fecha (fecha_envio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de eventos
CREATE TABLE IF NOT EXISTS eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    tipo ENUM('academico', 'administrativo', 'social', 'otro') DEFAULT 'academico',
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME,
    ubicacion VARCHAR(200),
    creado_por INT NOT NULL,
    estado ENUM('active', 'inactive') DEFAULT 'active',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (creado_por) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_fecha_inicio (fecha_inicio),
    INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de pagos
CREATE TABLE IF NOT EXISTS pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estudiante_id INT NOT NULL,
    concepto VARCHAR(200) NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    periodo VARCHAR(20) NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    fecha_pago DATETIME,
    metodo_pago VARCHAR(50),
    referencia_pago VARCHAR(100),
    estado ENUM('pendiente', 'completado', 'vencido') DEFAULT 'pendiente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id) ON DELETE CASCADE,
    INDEX idx_estudiante (estudiante_id),
    INDEX idx_estado (estado),
    INDEX idx_periodo (periodo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuarios de prueba (contraseñas hasheadas con password_hash)
-- Contraseña para todos: admin123, coord123, prof123, sec123, it123 respectivamente
INSERT INTO usuarios (employee_id, username, password, nombre, email, telefono, rol, permisos, estado) VALUES
('EMP001', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos Administrador', 'admin@universidad.edu', '555-0001', 'administrador', '["all"]', 'active'),
('EMP002', 'coord.sistemas', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María Coordinadora Sistemas', 'coord.sistemas@universidad.edu', '555-0002', 'coordinador', '["manage_students","manage_teachers","assign_subjects","view_reports","manage_schedules"]', 'active'),
('EMP003', 'prof.martinez', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Roberto Martínez', 'prof.martinez@universidad.edu', '555-0003', 'maestro', '["view_students","manage_grades","create_assignments","view_schedule"]', 'active'),
('EMP004', 'sec.garcia', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ana García Secretaria', 'sec.garcia@universidad.edu', '555-0004', 'secretaria', '["manage_students","manage_documents","view_reports"]', 'active'),
('EMP005', 'it.support', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Luis Soporte IT', 'it.support@universidad.edu', '555-0005', 'soporte', '["manage_users","system_config","view_reports"]', 'active');

-- Insertar estudiantes de prueba (contraseña: est123)
INSERT INTO estudiantes (student_id, username, password, nombre, email, telefono, carrera, semestre, estado) VALUES
('STU1001', 'juan.perez', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan Pérez', 'juan.perez@universidad.edu', '555-1001', 'Ingeniería en Sistemas', 2, 'active'),
('STU1002', 'maria.gonzalez', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María González', 'maria.gonzalez@universidad.edu', '555-1002', 'Ingeniería en Sistemas', 1, 'active');

-- Insertar materias de ejemplo
INSERT INTO materias (codigo, nombre, creditos, carrera, semestre, descripcion, profesor_id, aula, cupo_maximo) VALUES
('MAT101', 'Matemática I', 4, 'Ingeniería en Sistemas', 1, 'Fundamentos de matemática', 3, 'Aula 105', 30),
('PRG101', 'Programación I', 4, 'Ingeniería en Sistemas', 1, 'Introducción a la programación', 3, 'Aula 201', 30),
('FIS101', 'Física I', 3, 'Ingeniería en Sistemas', 1, 'Física básica', 3, 'Aula 110', 30),
('ING101', 'Inglés I', 2, 'Ingeniería en Sistemas', 1, 'Inglés básico', 3, 'Aula 305', 30),
('MAT102', 'Matemática II', 4, 'Ingeniería en Sistemas', 2, 'Cálculo diferencial e integral', 3, 'Aula 106', 30),
('PRG102', 'Programación II', 4, 'Ingeniería en Sistemas', 2, 'Programación orientada a objetos', 3, 'Aula 202', 30);

-- Insertar inscripciones de ejemplo
INSERT INTO inscripciones (estudiante_id, materia_id, periodo, calificacion, asistencias, estado) VALUES
(1, 1, '2024-1', 85.5, 28, 'cursando'),
(1, 2, '2024-1', 90.0, 30, 'cursando'),
(2, 1, '2024-1', NULL, 25, 'cursando'),
(2, 2, '2024-1', NULL, 27, 'cursando');
