<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';
$id_cliente = $_GET['id'];

// Obtener el cliente existente
$sql = "SELECT * FROM Clientes WHERE id_cliente = $id_cliente";
$cliente = $conn->query($sql)->fetch_assoc();

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

    // Verifica si el correo electrónico ya existe en otro cliente
    $checkEmail = "SELECT * FROM Clientes WHERE email='$email' AND id_cliente != $id_cliente";
    $resultEmail = $conn->query($checkEmail);
    if ($resultEmail->num_rows > 0) {
        $_SESSION['error'] = "El correo electrónico ya está registrado por otro cliente.";
        header("Location: /TravelEase/crud/clientes/editar_cliente.php?id=$id_cliente");
        exit();
    }

    // Verifica si el número de identificación ya existe en otro cliente
    $checkID = "SELECT * FROM Clientes WHERE numero_identificacion='$numero_identificacion' AND id_cliente != $id_cliente";
    $resultID = $conn->query($checkID);
    if ($resultID->num_rows > 0) {
        $_SESSION['error'] = "El número de identificación ya está registrado por otro cliente.";
        header("Location: /TravelEase/crud/clientes/editar_cliente.php?id=$id_cliente");
        exit();
    }

    $sql = "UPDATE Clientes SET nombre='$nombre', primer_apellido='$primer_apellido', segundo_apellido='$segundo_apellido', tipo_identificacion='$tipo_identificacion', numero_identificacion='$numero_identificacion', numero_celular='$numero_celular', email='$email', direccion='$direccion', fecha_nacimiento='$fecha_nacimiento', genero='$genero' 
            WHERE id_cliente = $id_cliente";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Cliente actualizado con éxito.";
        header("Location: /TravelEase/crud/clientes/clientes.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: /TravelEase/crud/clientes/editar_cliente.php?id=$id_cliente");
        exit();
    }
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
    <div class="container mt-5">
        <h2>Editar Cliente</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $cliente['nombre']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="primer_apellido" class="form-label">Primer Apellido</label>
                <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" value="<?php echo $cliente['primer_apellido']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" value="<?php echo $cliente['segundo_apellido']; ?>">
            </div>
            <div class="mb-3">
                <label for="tipo_identificacion" class="form-label">Tipo de Identificación</label>
                <select id="tipo_identificacion" name="tipo_identificacion" class="form-select" required>
                    <option value="Cédula de Ciudadanía" <?php echo $cliente['tipo_identificacion'] == 'Cédula de Ciudadanía' ? 'selected' : ''; ?>>Cédula de Ciudadanía</option>
                    <option value="Cédula de Extranjería" <?php echo $cliente['tipo_identificacion'] == 'Cédula de Extranjería' ? 'selected' : ''; ?>>Cédula de Extranjería</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="numero_identificacion" class="form-label">Número de Identificación</label>
                <input type="text" class="form-control" id="numero_identificacion" name="numero_identificacion" value="<?php echo $cliente['numero_identificacion']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="numero_celular" class="form-label">Número Celular</label>
                <input type="text" class="form-control" id="numero_celular" name="numero_celular" value="<?php echo $cliente['numero_celular']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $cliente['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $cliente['direccion']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $cliente['fecha_nacimiento']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="genero" class="form-label">Género</label>
                <select id="genero" name="genero" class="form-select" required>
                    <option value="<?php echo $cliente['genero']; ?>" selected><?php echo $cliente['genero']; ?></option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
            <a href="clientes.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/footer.php'; ?>
