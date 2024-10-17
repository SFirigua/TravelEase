<?php
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/TravelEase/includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $numero_celular = $_POST['numero_celular'];
    $direccion = $_POST['direccion'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $genero = $_POST['genero'];

    $sql = "INSERT INTO Clientes (nombre, numero_celular, direccion, fecha_nacimiento, genero) 
            VALUES ('$nombre', '$numero_celular', '$direccion', '$fecha_nacimiento', '$genero')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Cliente agregado con éxito</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
    <div class="container mt-5">
        <h2>Agregar Cliente</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="numero_celular" class="form-label">Número Celular</label>
                <input type="text" class="form-control" id="numero_celular" name="numero_celular" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Correo Electronico</label>
                <input type="email" class="form-control" id="direccion" name="direccion" required>
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </div>
            <div class="mb-3">
                <label for="genero" class="form-label">Género</label>
                <select id="genero" name="genero" class="form-select" required>
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
