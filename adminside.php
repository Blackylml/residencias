<?php
session_start();

// Conexión a la base de datos
$host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "creditosdb";

$conn = new mysqli($host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener todos los alumnos
$sqlAlumnos = "SELECT id, matricula, nombre FROM alumnos";
$resultAlumnos = $conn->query($sqlAlumnos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Créditos</title>
    <style>
        body {
            background-image: url('https://aenui.org/wp-content/uploads/2014/07/university-background-04.jpg');
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: row;
            height: 100vh;
        }
        .container {
            display: flex;
            flex: 1;
        }
        .left-panel {
            display: flex;
            flex-direction: column;
            flex: 2;
            gap: 10px;
            padding: 10px;
        }
        .right-panel {
            flex: 3;
            padding: 10px;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            height: 100%;
        }
        .card-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
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
        .btn {
            padding: 5px 10px;
            background-color: #508C9B;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #134B70;
        }
        select, input[type="text"], button {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="search"] {
            margin-bottom: 10px;
        }
        button {
            background-color: #508C9B;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #134B70;
        }
        /* Estilo para la barra de desplazamiento */
        .scrollable-card {
            height: 100%;
            max-height: 90vh; /* Limita la altura al 90% del viewport */
            overflow-y: auto;
        }
        .scrollable-card::-webkit-scrollbar {
            width: 10px;
        }
        .scrollable-card::-webkit-scrollbar-thumb {
            background: #508C9B;
            border-radius: 5px;
        }
        .scrollable-card::-webkit-scrollbar-thumb:hover {
            background: #134B70;
        }

        .btn-container {
        display: table-cell;
        vertical-align: middle;
        text-align: center;

        }

        .btn-color-mode-switch {
        display: inline-block;
        margin: 0px;
        position: relative;
        }

        .btn-color-mode-switch > label.btn-color-mode-switch-inner {
        margin: 0px;
        width: 140px;
        height: 40px;
        background-color: #001F3F;
        border-radius: 26px;
        overflow: hidden;
        position: relative;
        transition: all 0.3s ease;
            /*box-shadow: 0px 0px 8px 0px rgba(17, 17, 17, 0.34) inset;*/
        display: block;
        color: #fff;
        }

        .btn-color-mode-switch > label.btn-color-mode-switch-inner:before {
        content: attr(data-on);
        position: absolute;
        font-size: 20px;
        font-weight: 600;
        top: 7px;
        right: 20px;
        color: #222;
        }

        .btn-color-mode-switch > label.btn-color-mode-switch-inner:after {
        content: attr(data-off);
        width: 70px;
        height: 25px;
        background: #fff;
        border-radius: 26px;
        position: absolute;
        font-size: 20px;
        display: flex;
        justify-content: center;
        left: 2px;
        top: 2px;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0px 0px 6px -2px #111;
        padding: 5px 0px;
        color: #222;
        font-weight: 600;
        }

        .btn-color-mode-switch input[type="checkbox"] {
        cursor: pointer;
        width: 50px;
        height: 25px;
        opacity: 0;
        position: absolute;
        top: 0;
        z-index: 1;
        margin: 0px;
        }

        .btn-color-mode-switch input[type="checkbox"]:checked + label.btn-color-mode-switch-inner {
        background-color: #001F3F;
        }

        .btn-color-mode-switch input[type="checkbox"]:checked + label.btn-color-mode-switch-inner:after {
        content: attr(data-on);
        left: 68px;
        }

        .btn-color-mode-switch input[type="checkbox"]:checked + label.btn-color-mode-switch-inner:before {
        content: attr(data-off);
        right: auto;
        left: 20px;
        }


    </style>
</head>
<body>
<link rel="stylesheet" href="style2.css">
    <div class="container">
        <!-- Panel izquierdo -->
        <div class="left-panel">
            <!-- Tarjeta superior: Detalles del crédito -->
            <div class="card" id="creditos-card">
                <div class="card-header">Detalles de Créditos</div>
                <div id="tabla-creditos">
                    <table>
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2">Seleccione un alumno para ver los detalles.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Tarjeta inferior: Agregar crédito -->
            <div class="card">
                <div class="card-header">Agregar Crédito</div>
                <form id="form-credito" action="agregar_credito.php" method="post">
                    <input type="hidden" name="alumno_id" id="alumno-id">
                    <label for="descripcion">Descripción:</label>
                    <input type="text" name="descripcion" id="descripcion" required>
                    <label for="valor">Valor:</label>
                    <div class="btn-container">
                    <label class="switch btn-color-mode-switch">
                    <input value="1" id="color_mode" name="color_mode" type="checkbox">
                     <label class="btn-color-mode-switch-inner" data-off="1" data-on="2" for="color_mode"></label>
    </label>

</div>

                </div>
                    <input type="hidden" name="valor" id="valor" value="1">
                    <button type="submit">Agregar Crédito</button>
                </form>
            </div>
        </div>
        <!-- Panel derecho: Lista de alumnos -->
        <div class="right-panel">
            <div class="card scrollable-card">
                <div class="card-header">Lista de Alumnos</div>
                <input type="search" id="busqueda" placeholder="Buscar por matrícula..." onkeyup="filtrarAlumnos()">
                <table id="tabla-alumnos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Matrícula</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultAlumnos->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['matricula']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td>
                                <button class="btn" onclick="cargarCreditos(<?php echo $row['id']; ?>)">Ver Créditos</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function cargarCreditos(alumnoId) {
            document.getElementById('alumno-id').value = alumnoId;

            // Solicitar créditos del alumno
            fetch('obtener_creditos.php?alumno_id=' + alumnoId)
                .then(response => response.json())
                .then(data => {
                    let tablaHtml = '<table><thead><tr><th>Descripción</th><th>Valor</th></tr></thead><tbody>';
                    
                    data.forEach(credito => {
                        tablaHtml += `<tr><td>${credito.descripcion}</td><td>${credito.valor}</td></tr>`;
                    });

                    tablaHtml += '</tbody></table>';
                    document.getElementById('tabla-creditos').innerHTML = tablaHtml;
                });
        }

        function filtrarAlumnos() {
            const input = document.getElementById('busqueda');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('tabla-alumnos');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) { // Ignora el encabezado
                const matricula = rows[i].getElementsByTagName('td')[1];
                if (matricula) {
                    const txtValue = matricula.textContent || matricula.innerText;
                    rows[i].style.display = txtValue.toLowerCase().includes(filter) ? '' : 'none';
                }
            }
        }

        document.getElementById('color_mode').addEventListener('change', function () {
    const hiddenInput = document.getElementById('valor');
    hiddenInput.value = this.checked ? "2" : "1";

});


    </script>
    
</body>
</html>
