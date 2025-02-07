<?php
session_start();

// Verifica si el usuario está logeado
if (!isset($_SESSION['nombre']) || !isset($_SESSION['matricula'])) {
    header("Location: login.php"); // Redirige al login si no hay sesión
    exit();
}

// Recupera los datos del usuario
$nombre = $_SESSION['nombre'];
$matricula = $_SESSION['matricula']; // Usamos la matrícula

// Conexión a la base de datos
$host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "creditosdb";

$conn = new mysqli($host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recupera el ID del alumno usando la matrícula
$sqlAlumno = "SELECT id FROM alumnos WHERE matricula = ?";
$stmtAlumno = $conn->prepare($sqlAlumno);
$stmtAlumno->bind_param("s", $matricula);
$stmtAlumno->execute();
$stmtAlumno->bind_result($alumno_id);
$stmtAlumno->fetch();
$stmtAlumno->close();

if (!$alumno_id) {
    die("No se encontró el alumno asociado a la matrícula.");
}

// Consulta para obtener los créditos del alumno
$sqlCreditos = "SELECT id, descripcion, valor FROM creditos WHERE alumno_id = ?";
$stmtCreditos = $conn->prepare($sqlCreditos);
$stmtCreditos->bind_param("i", $alumno_id);
$stmtCreditos->execute();
$result = $stmtCreditos->get_result();

$creditos = [];
while ($row = $result->fetch_assoc()) {
    $creditos[] = $row;
}

$stmtCreditos->close();
$conn->close();

// Calcula el progreso
$totalCreditos = 5;
$creditosCompletados = count($creditos);
$progreso = ($creditosCompletados / $totalCreditos) * 100;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Gestión Créditos</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 100vh;
            background-image: url('https://aenui.org/wp-content/uploads/2014/07/university-background-04.jpg');
            background-size: cover;
            background-position: center;
            color: white;
        }
        h1, h2, h3 {
            margin: 0;
            padding: 10px;
        }
        table{
            background-color: white;
            text-align: left;
            border-collapse: collapse;
            width: 100%;
            color: black;
        }

        th, td{
            padding: 20px;
        }

        thead{
            background-color: #001F3F;
            border-bottom: solid 5px #3A6D8C;
            color: white;
        }

        tr:nth-child(even){
            background-color: #ddd;
        }

        tr:hover td{
            background-color: #3A6D8C;
            color: white;
        }
        /* Barra de progreso */
        .progress-container {
            width: 80%;
            background-color: #e0e0e0;
            border-radius: 25px;
            overflow: hidden;
            margin: 20px auto;
        }
        .progress-bar {
            height: 30px;
            background-color: #508C9B;
            width: <?php echo $progreso; ?>%;
            transition: width 0.5s;
        }
        .progress-text {
            text-align: center;
            margin-top: 10px;
            font-size: 18px;
            color: white;
        }
        .btn {
            display: inline-block;
            margin: 20px auto;
            padding: 10px 20px;
            color: white;
            background-color: #508C9B;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #134B70;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Tecnológico Superior De Monclova</h1>
        <h2>Sistema Gestión De Créditos</h2>
        <h3>Bienvenido, <?php echo htmlspecialchars($nombre); ?> (Matrícula: <?php echo htmlspecialchars($matricula); ?>)</h3>

        <!-- Barra de progreso -->
<div class="progress-container">
    <div class="progress-bar" style="width: <?php echo ($creditosCompletados / $totalCreditos) * 100; ?>%;"></div>
</div>
<div class="progress-text"><?php echo $creditosCompletados; ?> de <?php echo $totalCreditos; ?> créditos completados</div>


        <!-- Tabla de créditos -->
        <div class="card">
            <div class="card2">
                <p id="heading">Consulta De Créditos</p>
                <table>
                <thead>
    <tr>
        <th>Descripción</th>
        <th>Valor</th>
    </tr>
</thead>
<tbody>
    <?php if (!empty($creditos)): ?>
        <?php foreach ($creditos as $credito): ?>
            <tr>
                <td><?php echo htmlspecialchars($credito['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($credito['valor']); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="3">No se encontraron créditos.</td>
        </tr>
    <?php endif; ?>
</tbody>
</table>
<?php if ($creditosCompletados < $totalCreditos): ?>
    <form action="subir_documentos.php" method="post" enctype="multipart/form-data">
    <input type="file" name="archivo" id="archivo" required>
    <button type="subm'it">Subir documento</button>
</form>

<?php endif; ?>
</div>
</div>
</div>
</body>
</html>
