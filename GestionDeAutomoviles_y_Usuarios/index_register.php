<?php
// index_register.php
// --- 1. Iniciar la sesión ---
session_start();

// --- 2. Comprobamos si el usuario es admin ---
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    // Si no es admin, lo expulsamos al login
    header('Location: login.php');
    exit;
}

// --- 3. Incluir la conexión a la BD ---
require 'config_login.php';

// --- 4. Inicializar mensaje de error ---
$error_message = "";
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Registrar nuevo empleado - HospitalDavante</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="css/style_registers.css">
</head>

<body>

    <div class="card">
        <h2>Registrar Nuevo Empleado</h2>

        <!-- Mensaje de error -->
        <?php if (!empty($error_message)): ?>
            <div class="error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <!-- Formulario para registrar un nuevo empleado -->
        <form action="guardar_users.php" method="post" autocomplete="off">
            <label for="nom_user">Nombre de usuario *</label>
            <input id="nom_user" name="nom_user" type="text" required>

            <label for="email_user">Email</label>
            <input id="email_user" name="email_user" type="email"
                required pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">

            <label for="pass_user">Contraseña *</label>
            <input id="pass_user" name="pass_user" type="password" required>

            <label for="confirm_pass_user">Confirmar contraseña *</label>
            <input id="confirm_pass_user" name="confirm_pass_user" type="password" required>

            <label for="rol_id">Asignar rol</label>
            <select name="rol_id" id="rol_id">
                <option value="1">Administrador</option>
                <option value="2">Trabajador</option>
            </select>

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>

        <div class="links">
            <a href="gestor_users.php">
                <button class="btn btn-primary" style="margin-top: 5px;">Volver al gestor de empleados</button>
            </a>
        </div>
    </div>

</body>

</html>