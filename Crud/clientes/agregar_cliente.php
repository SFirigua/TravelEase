<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $primer_apellido = $_POST['primer_apellido'];
    $segundo_apellido = $_POST['segundo_apellido'];
    $tipo_identificacion = $_POST['tipo_identificacion'];
    $numero_identificacion = $_POST['numero_identificacion'];
    $numero_celular = $_POST['numero_celular'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $genero = $_POST['genero'];

    // Verifica si el correo electrónico ya existe
    $checkEmail = "SELECT * FROM Clientes WHERE email='$email'";
    $resultEmail = $conn->query($checkEmail);

    if ($resultEmail->num_rows > 0) {
        $_SESSION['error'] = "El correo electrónico ya está registrado.";
        header("Location: /TravelEase/crud/clientes/agregar_cliente.php");
        exit();
    }

    // Verifica si el número de identificación ya existe
    $checkID = "SELECT * FROM Clientes WHERE numero_identificacion='$numero_identificacion'";
    $resultID = $conn->query($checkID);

    if ($resultID->num_rows > 0) {
        $_SESSION['error'] = "El número de identificación ya está registrado.";
        header("Location: /TravelEase/crud/clientes/agregar_cliente.php");
        exit();
    }

    $sql = "INSERT INTO Clientes (nombre, primer_apellido, segundo_apellido, tipo_identificacion, numero_identificacion, numero_celular, email, direccion, fecha_nacimiento, genero) 
            VALUES ('$nombre', '$primer_apellido', '$segundo_apellido', '$tipo_identificacion', '$numero_identificacion', '$numero_celular', '$email', '$direccion', '$fecha_nacimiento', '$genero')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Cliente agregado con éxito";
        header("Location: /TravelEase/crud/clientes/clientes.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: /TravelEase/crud/clientes/clientes.php");
        exit();
    }
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
    <div class="container mt-5">
        <h2>Agregar Cliente</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar mensaje de éxito -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required 
                           pattern="^[A-ZÁÉÍÓÚÑ][a-záéíóúñA-ZÁÉÍÓÚÑ\s]{0,29}$" 
                           title="El nombre debe comenzar con una letra mayúscula, solo debe contener letras y un máximo de 30 caracteres.">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="primer_apellido" class="form-label">Primer Apellido</label>
                    <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" required 
                           pattern="^[A-ZÁÉÍÓÚÑ][a-záéíóúñA-ZÁÉÍÓÚÑ\s]{0,19}$" 
                           title="El apellido debe comenzar con una letra mayúscula, solo debe contener letras y un máximo de 20 caracteres.">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                    <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido"
                           pattern="^[A-ZÁÉÍÓÚÑ][a-záéíóúñA-ZÁÉÍÓÚÑ\s]{0,19}$" 
                           title="El apellido debe comenzar con una letra mayúscula, solo debe contener letras y un máximo de 20 caracteres.">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tipo_identificacion" class="form-label">Tipo de Identificación</label>
                    <select id="tipo_identificacion" name="tipo_identificacion" class="form-select" required>
                        <option value="" disabled selected>Selecciona el tipo de identificación</option>
                        <option value="Cédula de Ciudadanía">Cédula de Ciudadanía</option>
                        <option value="Cédula de Extranjería">Cédula de Extranjería</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="numero_identificacion" class="form-label">Número de Identificación</label>
                    <input type="text" class="form-control" id="numero_identificacion" name="numero_identificacion" required 
                           pattern="^\d{6,12}$" 
                           title="El número de identificación debe contener entre 6 y 12 dígitos.">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="numero_celular" class="form-label">Número Celular</label>
                    <input type="text" class="form-control" id="numero_celular" name="numero_celular" required 
                           pattern="^\d{10}$" 
                           title="El número celular debe contener exactamente 10 dígitos.">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" required 
                           pattern="^[A-Za-z0-9\s,#-]{5,100}$" 
                           title="La dirección debe contener entre 5 y 100 caracteres y puede incluir letras, números y símbolos (#, -).">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="genero" class="form-label">Género</label>
                    <select id="genero" name="genero" class="form-select" required>
                        <option value="" disabled selected>Selecciona el género</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Agregar Cliente</button>
            <a href="clientes.php" class="btn btn-secondary mt-3">Cancelar</a>
        </form>
    </div>
</main>


<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
