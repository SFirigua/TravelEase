-- Crear la base de datos: travelease
CREATE DATABASE IF NOT EXISTS travelease;
USE travelease;

CREATE TABLE Rutas (
    id_ruta INT AUTO_INCREMENT PRIMARY KEY,
    nombre_ruta VARCHAR(255) NOT NULL,
    origen VARCHAR(100) NOT NULL,
    destino VARCHAR(100) NOT NULL,
    duracion TIME NOT NULL,
    frecuencia ENUM('Diaria', 'Semanal', 'Mensual') DEFAULT 'Diaria'
);

CREATE TABLE Clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    primer_apellido VARCHAR(100),
    segundo_apellido VARCHAR(100),
    tipo_identificacion ENUM('Cédula de Ciudadanía', 'Cédula de Extranjería') NOT NULL,
    numero_identificacion VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    numero_celular VARCHAR(15),
    direccion VARCHAR(255),
    fecha_nacimiento DATE,
    genero ENUM('M', 'F', 'Otro')
);

CREATE TABLE Transportes (
    id_transporte INT AUTO_INCREMENT PRIMARY KEY,
    id_ruta INT,
    tipo_transporte ENUM('Avión', 'Tren', 'Autobús') NOT NULL,
    nombre_transporte VARCHAR(100) NOT NULL,
    num_asientos INT NOT NULL,
    tiempo_duracion TIME NOT NULL,
    FOREIGN KEY (id_ruta) REFERENCES Rutas(id_ruta)
);

CREATE TABLE Viajes (
    id_viaje INT AUTO_INCREMENT PRIMARY KEY,
    id_transporte INT,
    id_ruta INT,
    fecha_salida DATE NOT NULL,
    hora_salida TIME NOT NULL,
    fecha_llegada DATE NOT NULL,
    hora_llegada TIME NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    estado ENUM('Programado', 'Cancelado', 'En curso', 'Finalizado') DEFAULT 'Programado',
    FOREIGN KEY (id_transporte) REFERENCES Transportes(id_transporte),
    FOREIGN KEY (id_ruta) REFERENCES Rutas(id_ruta)
);

CREATE TABLE Reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    id_viaje INT,
    id_ruta INT,
    id_transporte INT,
    fecha_reserva DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
    reservas_vendidas INT NOT NULL,
    asiento ENUM('Economica', 'Premium', 'Ejecutiva') NOT NULL,
    estado ENUM('Pendiente', 'Confirmada', 'Cancelada') DEFAULT 'Pendiente',
    FOREIGN KEY (id_cliente) REFERENCES Clientes(id_cliente),
    FOREIGN KEY (id_viaje) REFERENCES Viajes(id_viaje)
    FOREIGN KEY (id_ruta) REFERENCES Rutas(id_ruta),
    FOREIGN KEY (id_transporte) REFERENCES Transportes(id_transporte)
);
