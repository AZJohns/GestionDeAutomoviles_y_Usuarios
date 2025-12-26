<?php
// eliminar_vehiculo.php
session_start();
require 'config_login.php';

// Guardián: Solo Admin (o el rol que decidas) puede eliminar
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    // Si no es Admin, lo expulsa
    die("Acceso denegado. No tienes permisos para realizar esta acción.");
}

// Obtenemos el ID del coche desde la URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: gestor_vehiculos.php');
    exit;
}

// Preparamos y ejecutamos la consulta DELETE
try {
    $sql = "DELETE FROM tbautomoviles WHERE id_auto = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // Redirigimos de vuelta al gestor
    header('Location: gestor_vehiculos.php?msg=eliminado');
    exit;

} catch (PDOException $e) {
    die("Error al eliminar el vehículo: " . $e->getMessage());
}
?>