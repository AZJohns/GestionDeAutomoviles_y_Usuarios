<?php
// editar_usuario.php
session_start();
require 'config_login.php';

// 1. Guardián: Solo Admin
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    die("Acceso denegado.");
}

// 2. Obtener ID del usuario a editar
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: gestor_users.php');
    exit;
}

// 3. Buscar los datos actuales del usuario
try {
    $sql = "SELECT nom_user, email_user, rol_id FROM tbusuarios WHERE id_usuario = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if ($user === false) {
        die("Usuario no encontrado.");
    }
} catch (PDOException $e) {
    die("Error al consultar la base de datos: " . $e->getMessage());
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Editar Empleado</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="css/style_registers.css"> </head>
<body>
    <div class="card">
        <h2>Editando a: <?= htmlspecialchars($user['nom_user']) ?></h2>
        
        <form action="actualizar_usuario.php" method="post" autocomplete="off">
            
            <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($id) ?>">

            <label for="nom_user">Nombre de usuario *</label>
            <input id="nom_user" name="nom_user" type="text" value="<?= htmlspecialchars($user['nom_user']) ?>" required>
            
            <label for="email_user">Email *</label>
            <input id="email_user" name="email_user" type="email" value="<?= htmlspecialchars($user['email_user']) ?>" required>
            
            <label for="rol_id">Asignar rol *</label>
            <select id="rol_id" name="rol_id" required>
                <option value="1" <?= ($user['rol_id'] == 1) ? 'selected' : '' ?>>Administrador</option>
                <option value="2" <?= ($user['rol_id'] == 2) ? 'selected' : '' ?>>Vendedor</option>
            </select>
            
            <hr style="margin: 20px 0;">
            
            <p><strong>Cambiar contraseña (opcional)</strong><br>
            <small>Deja estos campos vacíos para no cambiar la contraseña actual.</small></p>
            
            <label for="pass_user">Nueva Contraseña</label>
            <input id="pass_user" name="pass_user" type="password">
            
            <label for="confirm_pass_user">Confirmar Nueva Contraseña</label>
            <input id="confirm_pass_user" name="confirm_pass_user" type="password">
            
            <button type="submit" class="btn btn-primary">Actualizar Empleado</button>
        </form>
        
        <a href="gestor_users.php" class="btn btn-secondary" style="margin-top: 15px;">Cancelar y Volver</a>
    </div>
</body>
</html>