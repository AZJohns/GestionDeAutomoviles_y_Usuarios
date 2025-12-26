<?php
// index_clientes.php

// --- 1. Iniciar la sesión ---
session_start();
// --- 2. Incluir la conexión a la BD ---
require 'config_login.php';

// Comprobamos si el usuario es admin
// ( permitir Rol 1 y 2):
if (!isset($_SESSION['rol_id']) || ($_SESSION['rol_id'] != 1 && $_SESSION['rol_id'] != 2)) {
    // Si no es admin (1) NI empleado (2), lo expulsamos
    header('Location: login.php');
    exit;
}
$error_message = "";

?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Registro de nuevo vehiculo</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="css/style-privado.css">
</head>

<body>
    <div class="card">
        <h2>Registrar nuevo vehiculo </h2>
        <!-- Mensaje de error -->
        <?php if (!empty($error_message)): ?>
            <div class="error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <!-- Formulario para registrar un nuevo vehiculo -->
        <form action="guardar_vehiculos.php" method="post" autocomplete="off">
            <label for="marca_auto">Marca *</label>
            <input id="marca_auto" name="marca_auto" type="text" required>

            <label for="modelo_auto">Modelo *</label>
            <input id="modelo_auto" name="modelo_auto" type="text" required>

            <label for="anio_auto">Año *</label>
            <input id="anio_auto" name="anio_auto" type="number" required>

            <label for="precio_auto">Precio *</label>
            <input id="precio_auto" name="precio_auto" type="text" required>

            <label for="descripcion_auto">Descripción</label>
            <textarea id="descripcion_auto" name="descripcion_auto" rows="4"></textarea>

            <label for="foto_auto">Foto del vehículo</label>
            <input id="foto_auto" name="foto_auto" type="file" accept="image/jpeg, image/png">

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>

        <a href="panel_admin.php">
            <button class="btn btn-primary " style="margin-top: 5px">Volver a menu de admin</button>
        </a>
    </div>
</body>

</html>