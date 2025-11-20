-- ==========================================
--   CREACIÓN DE BASE DE DATOS
-- ==========================================
CREATE DATABASE IF NOT EXISTS sistema_trabajos;
USE sistema_trabajos;

-- ==========================================
--   TABLA: USUARIO
-- ==========================================
CREATE TABLE Usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    tipo_usuario ENUM('Administrador', 'Instructor', 'Aprendiz') NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    correo VARCHAR(120) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo'
);

-- ==========================================
--   TABLA: FICHA / CURSO
-- ==========================================
CREATE TABLE Ficha (
    id_ficha INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    programa VARCHAR(150) NOT NULL,
    jornada VARCHAR(50),
    fecha_inicio DATE,
    fecha_fin DATE
);

-- ==========================================
--   TABLA: INSTRUCTOR
-- ==========================================
CREATE TABLE Instructor (
    id_instructor INT PRIMARY KEY,
    area VARCHAR(150),
    FOREIGN KEY (id_instructor) REFERENCES Usuario(id_usuario)
);

-- ==========================================
--   TABLA: APRENDIZ
-- ==========================================
CREATE TABLE Aprendiz (
    id_aprendiz INT PRIMARY KEY,
    documento VARCHAR(20) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    id_ficha INT NOT NULL,
    FOREIGN KEY (id_aprendiz) REFERENCES Usuario(id_usuario),
    FOREIGN KEY (id_ficha) REFERENCES Ficha(id_ficha)
);

-- ==========================================
--   TABLA INTERMEDIA: INSTRUCTOR-FICHA (N..N)
-- ==========================================
CREATE TABLE Instructor_Ficha (
    id_instructor_ficha INT AUTO_INCREMENT PRIMARY KEY,
    id_instructor INT NOT NULL,
    id_ficha INT NOT NULL,
    FOREIGN KEY (id_instructor) REFERENCES Instructor(id_instructor),
    FOREIGN KEY (id_ficha) REFERENCES Ficha(id_ficha)
);

-- ==========================================
--   TABLA: TRABAJO
-- ==========================================
CREATE TABLE Trabajo (
    id_trabajo INT AUTO_INCREMENT PRIMARY KEY,
    id_aprendiz INT NOT NULL,
    id_ficha INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    archivo VARCHAR(255),
    fecha_subida DATETIME NOT NULL,
    fecha_limite DATETIME NOT NULL,
    fecha_modificacion DATETIME,
    estado ENUM('Pendiente', 'Enviado', 'Calificado') DEFAULT 'Pendiente',
    FOREIGN KEY (id_aprendiz) REFERENCES Aprendiz(id_aprendiz),
    FOREIGN KEY (id_ficha) REFERENCES Ficha(id_ficha)
);

-- ==========================================
--   TABLA: CALIFICACION
-- ==========================================
CREATE TABLE Calificacion (
    id_calificacion INT AUTO_INCREMENT PRIMARY KEY,
    id_trabajo INT UNIQUE NOT NULL,
    id_instructor INT NOT NULL,
    nota ENUM('A', 'D') NOT NULL,
    comentario TEXT,
    fecha_calificacion DATETIME NOT NULL,
    FOREIGN KEY (id_trabajo) REFERENCES Trabajo(id_trabajo),
    FOREIGN KEY (id_instructor) REFERENCES Instructor(id_instructor)
);