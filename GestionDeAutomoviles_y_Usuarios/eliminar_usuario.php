<?php
// eliminar_usuario.php
session_start();
require 'config_login.php';

// 1. Guardián: Solo Admin puede eliminar
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    die("Acceso denegado.");
}

// 2. Obtenemos el ID del usuario desde la URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: gestor_users.php');
    exit;
}

// 3. ¡MUY IMPORTANTE! Impedir que el admin se borre a sí mismo
if ($id == $_SESSION['id_usuario']) {
    die("Error: No puedes eliminar tu propia cuenta. <a href='gestor_users.php'>Volver</a>");
}

// 4. Preparamos y ejecutamos la consulta DELETE
try {
    $sql = "DELETE FROM tbusuarios WHERE id_usuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // 5. Redirigimos de vuelta al gestor
    header('Location: gestor_users.php');
    exit;

} catch (PDOException $e) {
    die("Error al eliminar el usuario: " . $e->getMessage());
}
?>
