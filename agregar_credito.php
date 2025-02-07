<?php
$host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "creditosdb";

$conn = new mysqli($host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Validar y procesar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alumno_id = $_POST['alumno_id'];
    $descripcion = $_POST['descripcion'];
    $valor = $_POST['valor'];

    if (!empty($alumno_id) && !empty($descripcion) && !empty($valor)) {
        $sql = "INSERT INTO creditos (alumno_id, descripcion, valor) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $alumno_id, $descripcion, $valor);
        if ($stmt->execute()) {
            echo "Crédito agregado exitosamente.";
        } else {
            echo "Error al agregar el crédito.";
        }
        $stmt->close();
    } else {
        echo "Todos los campos son obligatorios.";
    }
}
$conn->close();
header("Location: adminside.php"); // Cambia al nombre de tu archivo principal
exit();
?>
