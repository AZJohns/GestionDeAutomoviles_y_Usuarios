<?php
// guardar_users.php

session_start();
require 'config_login.php'; // Tu conexión $pdo

// Función para limpiar datos
function limpiar($s)
{
    return trim($s);
}

// POR ESTO (para permitir Rol 1 y 2):
// POR ESTO (para permitir Rol 1 y 2):
if (!isset($_SESSION['rol_id']) || ($_SESSION['rol_id'] != 1 && $_SESSION['rol_id'] != 2)) {
    // Si no es admin (1) NI empleado (2), lo expulsamos
    header('Location: login.php');
    exit;
}

// 2. Recogemos los datos del formulario de vehiculos
$marca_auto = isset($_POST['marca_auto']) ? limpiar($_POST['marca_auto']) : '';
$modelo_auto = isset($_POST['modelo_auto']) ? limpiar($_POST['modelo_auto']) : '';
$anio_auto = isset($_POST['anio_auto']) ? limpiar($_POST['anio_auto']) : '';
$precio_auto = isset($_POST['precio_auto']) ? limpiar($_POST['precio_auto']) : '';
$descripcion_auto = isset($_POST['descripcion_auto']) ? limpiar($_POST['descripcion_auto']) : '';

// 3. Validación básica para los campos de vehiculos
$errores = [];
if ($marca_auto === '') $errores[] = "La marca es obligatoria.";
if ($modelo_auto === '') $errores[] = "El modelo es obligatorio.";
if ($anio_auto === '') $errores[] = "El año es obligatorio.";
if ($precio_auto === '') $errores[] = "El precio es obligatorio.";

$ruta_foto_bd = null; // Variable para guardar la ruta en la BD

// Comprobamos si se subió un archivo y si no hubo errores
if (isset($_FILES['foto_auto']) && $_FILES['foto_auto']['error'] === UPLOAD_ERR_OK) {

    $file_tmp_path = $_FILES['foto_auto']['tmp_name'];
    $file_name = $_FILES['foto_auto']['name'];
    $file_size = $_FILES['foto_auto']['size'];
    $file_type = $_FILES['foto_auto']['type'];

    // Obtenemos la extensión del archivo
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Extensiones permitidas
    $allowed_ext = ['jpg', 'jpeg', 'png'];

    if (in_array($file_ext, $allowed_ext)) {
        // Creamos un nombre de archivo único para evitar sobreescribir
        $new_file_name = uniqid('', true) . '.' . $file_ext;
        $upload_dir = 'uploads/'; // ¡DEBES CREAR ESTA CARPETA!
        $dest_path = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp_path, $dest_path)) {
            $ruta_foto_bd = $dest_path; // Esta es la ruta que guardamos en la BD
        } else {
            $errores[] = "Error al mover el archivo subido.";
        }
    } else {
        $errores[] = "Tipo de archivo no permitido (solo jpg, jpeg, png).";
    }
}

// 4. Si hay errores, los mostramos
if (!empty($errores)) {
    echo "<h3>Errores:</h3><ul>";
    foreach ($errores as $e) {
        echo "<li>" . htmlspecialchars($e) . "</li>";
    }
    // Damos un enlace para volver al formulario de registro de vehiculos
    echo "</ul><p><a href='gestor_vehiculos.php'>Volver al gestor</a></p>";
    exit;
}

// 5. Comprobar si el vehiculo ya existe
try {
    $sql_check = "SELECT * FROM tbautomoviles WHERE marca_auto = ? AND modelo_auto = ? AND anio_auto = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$marca_auto, $modelo_auto, $anio_auto]);

    if ($stmt_check->fetch()) {
        die("Error: El vehiculo ya está registrado. <a href='gestor_vehiculos.php'>Volver</a>");
    }
} catch (PDOException $e) {
    die("Error al comprobar la base de datos: " . $e->getMessage());
}


// 6. Si no hay errores, procedemos a insertar en la BD
try {

    // Apuntamos a la tabla 'tbautomoviles' y sus columnas
    $sql = "INSERT INTO tbautomoviles 
                (marca_auto, modelo_auto, anio_auto, precio_auto, descripcion_auto, foto_auto, estado_auto) 
            VALUES 
                (:marca_auto, :modelo_auto, :anio_auto, :precio_auto, :descripcion_auto, :foto_auto, :estado_auto)";
    $stmt = $pdo->prepare($sql);

    // Ejecutamos con los datos del formulario de vehiculos
    $stmt->execute([
        ':marca_auto'   => $marca_auto,
        ':modelo_auto'  => $modelo_auto,
        ':anio_auto'    => $anio_auto,
        ':precio_auto'  => $precio_auto,
        ':descripcion_auto' => $descripcion_auto,
        ':foto_auto'        => $ruta_foto_bd,
        ':estado_auto'      => 'disponible'
    ]);

    // 7. Redirigir al panel principal con un mensaje de éxito
    echo "¡Vehículo registrado con éxito! Redirigiendo...";
    header("Refresh: 2; URL=gestor_vehiculos.php"); // Redirige al panel en 2 segundos
    exit;
} catch (PDOException $e) {
    // Si algo falla en la inserción
    echo "<h3>Error al guardar el nuevo vehículo:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    // Damos un enlace para volver al formulario de registro de vehiculos
    echo "<p><a href='gestor_vehiculos.php'>Volver</a></p>";
}
