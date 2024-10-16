-- Crear la base de datos: travelease
CREATE TABLE Clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    primer_apellido VARCHAR(100) NOT NULL,
    segundo_apellido VARCHAR(100) DEFAULT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    numero_celular VARCHAR(15),
    direccion VARCHAR(255),
    fecha_nacimiento DATE, 
    genero ENUM('M', 'F', 'Otro') 
);

CREATE TABLE Transportes (
    id_transporte INT AUTO_INCREMENT PRIMARY KEY,
    tipo_transporte ENUM('Avión', 'Tren', 'Autobús') NOT NULL,
    nombre_transporte VARCHAR(100) NOT NULL,
    num_asientos INT NOT NULL
);

CREATE TABLE Viajes (
    id_viaje INT AUTO_INCREMENT PRIMARY KEY,
    id_transporte INT,
    origen VARCHAR(100) NOT NULL,
    destino VARCHAR(100) NOT NULL,
    fecha_salida DATE NOT NULL,
    hora_salida TIME NOT NULL, 
    fecha_llegada DATE NOT NULL,
    hora_llegada TIME NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
 estado ENUM('Programado', 'Cancelado', 'En curso', 'Finalizado') DEFAULT 'Programado',
 FOREIGN KEY (id_transporte) REFERENCES Transportes(id_transporte)
);

CREATE TABLE Rutas (
    id_ruta INT AUTO_INCREMENT PRIMARY KEY,
    id_transporte INT,
    origen VARCHAR(100) NOT NULL,
    destino VARCHAR(100) NOT NULL,
    duracion TIME NOT NULL,
    paradas_intermedias VARCHAR(255),
    frecuencia ENUM('Diaria', 'Semanal', 'Mensual') DEFAULT 'Diaria',
    FOREIGN KEY (id_transporte) REFERENCES Transportes(id_transporte)
);

CREATE TABLE Paradas (
    id_parada INT AUTO_INCREMENT PRIMARY KEY,
    id_ruta INT,
    nombre_parada VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_ruta) REFERENCES Rutas(id_ruta)
);

CREATE TABLE Reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    id_viaje INT,
    fecha_reserva DATE NOT NULL,
    reservas_vendidas INT NOT NULL,
    estado ENUM('Pendiente', 'Confirmada', 'Cancelada') DEFAULT 'Pendiente',
    FOREIGN KEY (id_cliente) REFERENCES Clientes(id_cliente),
    FOREIGN KEY (id_viaje) REFERENCES Viajes(id_viaje)
);

CREATE TABLE Asientos (
    id_asiento INT AUTO_INCREMENT PRIMARY KEY,
    id_viaje INT,
    id_reserva INT,
    numero_asiento VARCHAR(10) NOT NULL UNIQUE,
    clase ENUM('Economica', 'Premium', 'Ejecutiva') NOT NULL,
    FOREIGN KEY (id_viaje) REFERENCES Viajes(id_viaje),
    FOREIGN KEY (id_reserva) REFERENCES Reservas(id_reserva)
);