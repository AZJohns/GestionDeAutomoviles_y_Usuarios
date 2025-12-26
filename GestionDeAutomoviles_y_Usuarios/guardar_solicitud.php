<?php
// guardar_solicitud.php
require 'config_login.php'; // Tu conexión $pdo

// 1. Comprobar que los datos llegan por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2. Recoger los datos del formulario
    $auto_id = $_POST['auto_id'] ?? null;
    $nombre = trim($_POST['nombre_interesado'] ?? '');
    $email = trim($_POST['email_interesado'] ?? '');
    $telefono = trim($_POST['telefono_interesado'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    // 3. Validación básica
    if (empty($auto_id) || empty($nombre) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Faltan datos obligatorios o el email es inválido. <a href='index.php'>Volver</a>");
    }

    // 4. Insertar la solicitud en la base de datos
    try {
        $sql = "INSERT INTO tbsolicitudes (auto_id, nombre_interesado, email_interesado, telefono_interesado, mensaje) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$auto_id, $nombre, $email, $telefono, $mensaje]);
        
        // 5. Redirigir a una página de éxito
        header('Location: solicitud_exitosa.php');
        exit;

    } catch (PDOException $e) {
        die("Error al guardar la solicitud: " . $e->getMessage());
    }

} else {
    // Si alguien accede directamente, lo echamos
    header('Location: index.php');
    exit;
}
?>