<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "creditosdb";

    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $sql = "SELECT nombre, matricula, password FROM alumnos WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_nombre, $db_matricula, $db_password);
        $stmt->fetch();

        if ($password === $db_password) {
            $_SESSION['nombre'] = $db_nombre;
            $_SESSION['matricula'] = $db_matricula;
            header("Location: menu.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Contraseña incorrecta.";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "El usuario no existe.";
        header("Location: index.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
