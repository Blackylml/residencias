<?php
session_start();

// Verifica si el usuario está logeado
if (!isset($_SESSION['nombre']) || !isset($_SESSION['matricula'])) {
    header("Location: login.php"); // Redirige al login si no hay sesión
    exit();
}

// Recupera los datos del usuario
$nombre = $_SESSION['nombre'];
$matricula = $_SESSION['matricula'];

// Procesar la subida del archivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
    if ($_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        // Obtener la fecha actual para crear la carpeta semanal
        $fechaActual = new DateTime();
        $semana = $fechaActual->format("Y-W"); // Formato: Año-NúmeroDeSemana (ej. 2023-42)

        // Crear la ruta de la carpeta semanal
        $carpetaSemanal = "uploads/$semana";

        // Crear la carpeta si no existe
        if (!file_exists($carpetaSemanal)) {
            mkdir($carpetaSemanal, 0777, true);
        }

        // Crear la carpeta del alumno (nombre y matrícula)
        $carpetaAlumno = "$carpetaSemanal/$nombre-$matricula";

        // Crear la carpeta del alumno si no existe
        if (!file_exists($carpetaAlumno)) {
            mkdir($carpetaAlumno, 0777, true);
        }

        // Obtener el nombre del archivo
        $nombreArchivo = basename($_FILES['archivo']['name']);
        $rutaArchivo = "$carpetaAlumno/$nombreArchivo";

        // Mover el archivo a la carpeta del alumno
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaArchivo)) {
            echo "<script>alert('Archivo subido correctamente.'); window.history.back();</script>";
        } else {
            echo "<script>alert('Error al subir el archivo.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Error: No se ha seleccionado un archivo o hubo un problema con la subida.'); window.history.back();</script>";
    }
} else {
    header("Location: menu_principal.php"); // Redirige si no es una solicitud POST
    exit();
}
?>