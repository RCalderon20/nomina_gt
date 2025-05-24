-- Crear base de datos y usarla
CREATE DATABASE nomina_gt2;
USE nomina_gt2;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(100) NOT NULL,
    rol ENUM('admin', 'rrhh') NOT NULL
);

-- Tabla de empleados
CREATE TABLE empleados (
    id_empleado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    dpi VARCHAR(15) NOT NULL UNIQUE,
    direccion VARCHAR(100),
    telefono VARCHAR(20),
    correo VARCHAR(100),
    fecha_ingreso DATE NOT NULL,
    departamento VARCHAR(100),
    estado ENUM('trabajando', 'baja') DEFAULT 'trabajando',
    fecha_baja DATE,
    salario_base DECIMAL(10,2) NOT NULL DEFAULT 0,
    tipo_pago ENUM('semanal', 'quincenal', 'mensual') NOT NULL DEFAULT 'mensual'
);

-- Tabla de nómina
CREATE TABLE nomina (
    id_nomina INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    nombre_empleado VARCHAR(100) NOT NULL,
    apellido_empleado VARCHAR(100) NOT NULL,
    departamento VARCHAR(100),
    tipo_pago VARCHAR(20), -- Semanal, Quincenal, Mensual
    salario_base DECIMAL(10,2) NOT NULL,
    horas_extra DECIMAL(10,2) DEFAULT 0,
    valor_horas_extra DECIMAL(10,2) DEFAULT 0,
    comisiones DECIMAL(10,2) DEFAULT 0,
    bonificacion DECIMAL(10,2) DEFAULT 250.00,
    igss DECIMAL(10,2) DEFAULT 0,
    isr DECIMAL(10,2) DEFAULT 0,
    total_devengado DECIMAL(10,2) DEFAULT 0,
    total_descuentos DECIMAL(10,2) DEFAULT 0,
    total_liquido DECIMAL(10,2) DEFAULT 0,
    fecha_generacion DATE NOT NULL,
    FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado)
);

CREATE TABLE prestamos_nomina (
    id_prestamo INT AUTO_INCREMENT PRIMARY KEY,
    id_nomina INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    descripcion VARCHAR(255),
    FOREIGN KEY (id_nomina) REFERENCES nomina(id_nomina)
);

CREATE TABLE liquidaciones (
    id_liquidacion INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT NOT NULL,
    fecha_liquidacion DATE NOT NULL,
    tipo ENUM('renuncia', 'despido') NOT NULL,
    salario_pendiente DECIMAL(10,2) DEFAULT 0,
    vacaciones_pendientes DECIMAL(10,2) DEFAULT 0,
    indemnizacion DECIMAL(10,2) DEFAULT 0,
    total_liquidacion DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado)
);


-- LOGIN
DELIMITER $$
CREATE PROCEDURE sp_autenticar_usuario (
    IN p_usuario VARCHAR(50),
    IN p_contrasena VARCHAR(255)
)
BEGIN
    SELECT * FROM usuarios
    WHERE nombre_usuario = p_usuario AND contrasena = p_contrasena;
END$$
DELIMITER ;

-- CRUD USUARIOS
-- LISTAR USUARIOS
DELIMITER $$
CREATE PROCEDURE sp_listar_usuarios()
BEGIN
    SELECT * FROM usuarios;
END$$
DELIMITER ;

-- OBTENER USUARIO POR ID
DELIMITER $$
CREATE PROCEDURE sp_obtener_usuario_por_id(IN p_id_usuario INT)
BEGIN
    SELECT * FROM usuarios WHERE id_usuario = p_id_usuario;
END$$
DELIMITER ;

-- CREAR USUARIO
DELIMITER $$
CREATE PROCEDURE sp_crear_usuario(
    IN p_nombre_usuario VARCHAR(50),
    IN p_contrasena VARCHAR(255),
    IN p_rol ENUM('admin', 'rrhh')
)
BEGIN
    INSERT INTO usuarios (nombre_usuario, contrasena, rol)
    VALUES (p_nombre_usuario, p_contrasena, p_rol);
END$$
DELIMITER ;

-- ACTUALIZAR USUARIO
DELIMITER $$
CREATE PROCEDURE sp_actualizar_usuario(
    IN p_id_usuario INT,
    IN p_nombre_usuario VARCHAR(50),
    IN p_contrasena VARCHAR(255),
    IN p_rol ENUM('admin', 'rrhh')
)
BEGIN
    UPDATE usuarios
    SET nombre_usuario = p_nombre_usuario,
        contrasena = p_contrasena,
        rol = p_rol
    WHERE id_usuario = p_id_usuario;
END$$
DELIMITER ;

-- ELIMINAR USUARIO
DELIMITER $$
CREATE PROCEDURE sp_eliminar_usuario(IN p_id_usuario INT)
BEGIN
    DELETE FROM usuarios WHERE id_usuario = p_id_usuario;
END$$
DELIMITER ;


-- CRUD EMPLEADOS
-- LISTAR EMPLEADOS
DELIMITER $$
CREATE PROCEDURE ObtenerEmpleados()
BEGIN
    SELECT * FROM empleados;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE ObtenerEmpleadoPorId(IN p_id INT)
BEGIN
    SELECT * FROM empleados WHERE id_empleado = p_id;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE CrearEmpleado(
    IN p_nombre VARCHAR(100),
    IN p_apellido VARCHAR(100),
    IN p_dpi VARCHAR(20),
    IN p_direccion TEXT,
    IN p_telefono VARCHAR(15),
    IN p_correo VARCHAR(100),
    IN p_fecha_ingreso DATE,
    IN p_departamento VARCHAR(50),
    IN p_estado VARCHAR(20),
    IN p_salario_base DECIMAL(10,2),
    IN p_tipo_pago VARCHAR(20)
)
BEGIN
    INSERT INTO empleados (nombre, apellido, dpi, direccion, telefono, correo, fecha_ingreso, departamento, estado, salario_base, tipo_pago)
    VALUES (p_nombre, p_apellido, p_dpi, p_direccion, p_telefono, p_correo, p_fecha_ingreso, p_departamento, p_estado, p_salario_base, p_tipo_pago);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE ActualizarEmpleado(
    IN p_id INT,
    IN p_nombre VARCHAR(100),
    IN p_apellido VARCHAR(100),
    IN p_dpi VARCHAR(20),
    IN p_direccion TEXT,
    IN p_telefono VARCHAR(15),
    IN p_correo VARCHAR(100),
    IN p_fecha_ingreso DATE,
    IN p_departamento VARCHAR(50),
    IN p_estado VARCHAR(20),
    IN p_salario_base DECIMAL(10,2),
    IN p_tipo_pago VARCHAR(20)
)
BEGIN
    UPDATE empleados
    SET nombre = p_nombre, apellido = p_apellido, dpi = p_dpi, direccion = p_direccion,
        telefono = p_telefono, correo = p_correo, fecha_ingreso = p_fecha_ingreso,
        departamento = p_departamento, estado = p_estado, salario_base = p_salario_base,
        tipo_pago = p_tipo_pago
    WHERE id_empleado = p_id;
END$$
DELIMITER ;

-- DAR DE BAJA 
DELIMITER $$
CREATE PROCEDURE CambiarEstadoABajaEmpleado(IN p_id INT)
BEGIN
    UPDATE empleados
    SET estado = 'baja'
    WHERE id_empleado = p_id;
END$$
DELIMITER ;


-- Procedimiento para obtener el estado del emplelado
DELIMITER $$
CREATE PROCEDURE ObtenerEmpleadosPorEstado(IN p_estado VARCHAR(50))
BEGIN
    SELECT * FROM empleados WHERE estado = p_estado;
END$$
DELIMITER ;


-- Obtener empleado por ID
DELIMITER //
CREATE PROCEDURE sp_obtener_empleado_por_id(IN p_id_empleado INT)
BEGIN
    SELECT id_empleado, nombre, apellido, salario_base, tipo_pago, departamento
    FROM empleados 
    WHERE id_empleado = p_id_empleado;
END;
//
DELIMITER ;

-- Insertar nómina
DELIMITER //
CREATE PROCEDURE sp_insertar_nomina(
    IN p_id_empleado INT, IN p_nombre VARCHAR(100), IN p_apellido VARCHAR(100),
    IN p_departamento VARCHAR(100), IN p_tipo_pago VARCHAR(50), IN p_salario_base DECIMAL(10,2),
    IN p_horas_extra DECIMAL(10,2), IN p_valor_horas_extra DECIMAL(10,2), IN p_comisiones DECIMAL(10,2),
    IN p_bonificacion DECIMAL(10,2), IN p_igss DECIMAL(10,2), IN p_isr DECIMAL(10,2),
    IN p_total_devengado DECIMAL(10,2), IN p_total_descuentos DECIMAL(10,2), IN p_total_liquido DECIMAL(10,2)
)
BEGIN
    INSERT INTO nomina (id_empleado, nombre_empleado, apellido_empleado, departamento, tipo_pago, salario_base, horas_extra, valor_horas_extra, comisiones, bonificacion, igss, isr, total_devengado, total_descuentos, total_liquido, fecha_generacion)
    VALUES (p_id_empleado, p_nombre, p_apellido, p_departamento, p_tipo_pago, p_salario_base, p_horas_extra, p_valor_horas_extra, p_comisiones, p_bonificacion, p_igss, p_isr, p_total_devengado, p_total_descuentos, p_total_liquido, NOW());
END;
//
DELIMITER ;

-- Insertar préstamo
DELIMITER //
CREATE PROCEDURE sp_insertar_prestamo(IN p_id_nomina INT, IN p_monto DECIMAL(10,2), IN p_descripcion TEXT)
BEGIN
    INSERT INTO prestamos_nomina (id_nomina, monto, descripcion)
    VALUES (p_id_nomina, p_monto, p_descripcion);
END;
//
DELIMITER ;


DELIMITER //
CREATE PROCEDURE ObtenerUltimoIdNomina()
BEGIN
    SELECT LAST_INSERT_ID() AS id_nomina;
END //
DELIMITER ;

-- Obtener nómina
DELIMITER //
CREATE PROCEDURE obtener_nomina()
BEGIN
    SELECT * FROM nomina;
END;
//
DELIMITER ;

-- Obtener préstamos asociados a la nómina
DELIMITER //
CREATE PROCEDURE obtener_prestamos()
BEGIN
    SELECT p.*, n.nombre_empleado, n.apellido_empleado
    FROM prestamos_nomina p
    JOIN nomina n ON p.id_nomina = n.id_nomina;
END;
//
DELIMITER ;

-- Obtener nomina por comisiones
DELIMITER $$
CREATE PROCEDURE obtenerNominaConComisiones()
BEGIN
    SELECT * 
    FROM nomina 
    WHERE comisiones > 0 
    ORDER BY comisiones DESC;
END$$
DELIMITER ;



-- Insertar usuario admin por defecto
INSERT INTO usuarios (nombre_usuario, contrasena, rol)
VALUES ('admin', 'admin123', 'admin');

-- Insertar usuario admin por defecto
INSERT INTO usuarios (nombre_usuario, contrasena, rol)
VALUES ('ram', 'admin123', 'rrhh');



select * from usuarios
select * from empleados
select * from nomina
select * from prestamos_nomina
