<?php
$host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "creditosdb";

$conn = new mysqli($host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$alumno_id = $_GET['alumno_id'] ?? 0;
$sql = "SELECT id, descripcion, valor FROM creditos WHERE alumno_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $alumno_id);
$stmt->execute();
$result = $stmt->get_result();

$creditos = [];
while ($row = $result->fetch_assoc()) {
    $creditos[] = $row;
}

echo json_encode($creditos);
$stmt->close();
$conn->close();
?>
