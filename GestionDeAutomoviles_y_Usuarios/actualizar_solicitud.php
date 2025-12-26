<?php
// actualizar_solicitud.php
session_start();
require 'config_login.php';

// 1. Guardián: Solo Admin (1) o Empleado (2)
if (!isset($_SESSION['rol_id']) || ($_SESSION['rol_id'] != 1 && $_SESSION['rol_id'] != 2)) {
    die("Acceso denegado.");
}

// 2. Obtener los datos de la URL
$id_solicitud = $_GET['id'] ?? null;
$nuevo_estado = $_GET['estado'] ?? null;

// 3. Validar los datos
if (!$id_solicitud || !in_array($nuevo_estado, ['contactado', 'descartado'])) {
    // Si los datos son incorrectos, simplemente volvemos al gestor
    header('Location: ver_solicitudes.php');
    exit;
}

// 4. Actualizar la base de datos
try {
    $sql = "UPDATE tbsolicitudes SET estado_solicitud = ? WHERE id_solicitud = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nuevo_estado, $id_solicitud]);

    // 5. Redirigir de vuelta al gestor de solicitudes
    header('Location: ver_solicitudes.php');
    exit;

} catch (PDOException $e) {
    die("Error al actualizar la solicitud: " . $e->getMessage());
}
?>