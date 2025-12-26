<?php
// guardar_users.php

session_start();
require 'config_login.php'; // Tu conexión $pdo

// Función para limpiar datos
function limpiar($s) {
    return trim($s);
}

// 1. Comprobamos si el usuario es admin
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    // Si no es admin, lo expulsamos al login
    header('Location: login.php');
    exit;
}

// 2. Recogemos los datos del formulario de empleados
$nom_user = isset($_POST['nom_user']) ? limpiar($_POST['nom_user']) : '';
$email_user = isset($_POST['email_user']) ? limpiar($_POST['email_user']) : '';
$pass_user = isset($_POST['pass_user']) ? limpiar($_POST['pass_user']) : '';
$confirm_pass_user = isset($_POST['confirm_pass_user']) ? limpiar($_POST['confirm_pass_user']) : '';
$rol_id = isset($_POST['rol_id']) ? limpiar($_POST['rol_id']) : '';

// 3. Validación básica para los campos de usuario
$errores = [];
if ($nom_user === '') $errores[] = "El nombre de usuario es obligatorio.";
if ($email_user === '') $errores[] = "El email es obligatorio.";
if ($pass_user === '') $errores[] = "La contraseña es obligatoria.";
if ($rol_id === '') $errores[] = "El rol es obligatorio.";

// Validación de email
if ($email_user !== '' && !filter_var($email_user, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "Email inválido.";
}

// Validación de contraseñas
if ($pass_user !== $confirm_pass_user) {
    $errores[] = "Las contraseñas no coinciden.";
}

// 4. Si hay errores, los mostramos
if (!empty($errores)) {
    echo "<h3>Errores:</h3><ul>";
    foreach ($errores as $e) {
        echo "<li>" . htmlspecialchars($e) . "</li>";
    }
    // Damos un enlace para volver al formulario de registro de empleados
    echo "</ul><p><a href='index_register.php'>Volver al formulario</a></p>"; 
    exit;
}

// 5. Comprobar si el usuario o email ya existen
try {
    $sql_check = "SELECT * FROM tbusuarios WHERE nom_user = ? OR email_user = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$nom_user, $email_user]);
    
    if ($stmt_check->fetch()) {
        die("Error: El nombre de usuario o el email ya están registrados. <a href='index_register.php'>Volver</a>");
    }

} catch (PDOException $e) {
    die("Error al comprobar la base de datos: " . $e->getMessage());
}


// 6. Si no hay errores, procedemos a insertar en la BD
// 6. HASHEAR LA CONTRASEÑA
$pass_hasheada = password_hash($pass_user, PASSWORD_DEFAULT);

// 7. Si no hay errores, procedemos a insertar en la BD
try {
    $sql = "INSERT INTO tbusuarios (nom_user, email_user, pass_user, rol_id) 
            VALUES (:nom_user, :email_user, :pass_user, :rol_id)";
    
    $stmt = $pdo->prepare($sql);
    
    // Ejecutamos con la contraseña HASHEADA
    $stmt->execute([
        ':nom_user'   => $nom_user,
        ':email_user' => $email_user,
        ':pass_user'  => $pass_hasheada, // <-- Variable cambiada
        ':rol_id'     => $rol_id
    ]);

    // 8. Redirigir al panel principal con un mensaje de éxito
    echo "¡Empleado registrado con éxito! Redirigiendo...";
    header("Refresh: 2; URL=panel_admin.php"); // Redirige al panel en 2 segundos
    exit;

} catch (PDOException $e) {
    // Si algo falla en la inserción
    echo "<h3>Error al guardar el nuevo empleado:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    // Damos un enlace para volver al formulario de registro de empleados
    echo "<p><a href='index_register.php'>Volver</a></p>"; 
}
?>