<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $primer_apellido = trim($_POST['primer_apellido']);
    $segundo_apellido = trim($_POST['segundo_apellido']);
    $numero_celular = trim($_POST['numero_celular']);
    $email = trim($_POST['email']);
    $direccion = trim($_POST['direccion']);
    $fecha_nacimiento = trim($_POST['fecha_nacimiento']);
    $genero = trim($_POST['genero']);

    // Validaciones
    if (!preg_match("/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/", $nombre)) {
        $_SESSION['error'] = "El nombre solo puede contener letras y espacios.";
        header("Location: /TravelEase/crud/clientes/clientes.php");
        exit();
    }

    if (!preg_match("/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/", $primer_apellido)) {
        $_SESSION['error'] = "El primer apellido solo puede contener letras y espacios.";
        header("Location: /TravelEase/crud/clientes/clientes.php");
        exit();
    }

    if (!empty($segundo_apellido) && !preg_match("/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/", $segundo_apellido)) {
        $_SESSION['error'] = "El segundo apellido solo puede contener letras y espacios.";
        header("Location: /TravelEase/crud/clientes/clientes.php");
        exit();
    }

    if (!preg_match("/^\d{9,15}$/", $numero_celular)) {
        $_SESSION['error'] = "El número celular debe contener solo dígitos y tener entre 9 y 15 caracteres.";
        header("Location: /TravelEase/crud/clientes/clientes.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "El correo electrónico no es válido.";
        header("Location: /TravelEase/crud/clientes/clientes.php");
        exit();
    }

    // Verifica si el correo electrónico ya existe
    $checkEmail = "SELECT * FROM Clientes WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "El correo electrónico ya está registrado.";
        header("Location: /TravelEase/crud/clientes/clientes.php");
        exit();
    } else {
        $sql = "INSERT INTO Clientes (nombre, primer_apellido, segundo_apellido, numero_celular, email, direccion, fecha_nacimiento, genero) 
                VALUES ('$nombre', '$primer_apellido', '$segundo_apellido', '$numero_celular', '$email', '$direccion', '$fecha_nacimiento', '$genero')";

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
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
    <div class="container mt-5">
        <h2>Agregar Cliente</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" title="El nombre solo puede contener letras y espacios.">
            </div>
            <div class="mb-3">
                <label for="primer_apellido" class="form-label">Primer Apellido</label>
                <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" title="El primer apellido solo puede contener letras y espacios.">
            </div>
            <div class="mb-3">
                <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" title="El segundo apellido solo puede contener letras y espacios.">
            </div>
            <div class="mb-3">
                <label for="numero_celular" class="form-label">Número Celular</label>
                <input type="text" class="form-control" id="numero_celular" name="numero_celular" required pattern="\d{9,15}" title="El número celular debe contener solo dígitos y tener entre 9 y 15 caracteres.">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </div>
            <div class="mb-3">
                <label for="genero" class="form-label">Género</label>
                <select id="genero" name="genero" class="form-select" required>
                    <option value="" disabled selected>Selecciona el género</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Cliente</button>
            <a href="clientes.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
