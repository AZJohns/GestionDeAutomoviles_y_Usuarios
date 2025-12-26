<?php
// editar_vehiculo.php
session_start();
require 'config_login.php';

// 1. Guardián: Solo Admin (1) o Empleado (2) pueden editar
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    // Si no es Admin, lo expulsa
    die("Acceso denegado. No tienes permisos para realizar esta acción.");
}

// 2. Obtener ID del vehículo a editar
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: gestor_vehiculos.php');
    exit;
}

// 3. Buscar los datos actuales del vehículo
try {
    $sql = "SELECT * FROM tbautomoviles WHERE id_auto = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $coche = $stmt->fetch();

    if ($coche === false) {
        die("Vehículo no encontrado.");
    }
} catch (PDOException $e) {
    die("Error al consultar la base de datos: " . $e->getMessage());
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Editar Vehículo</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="css/style_registers.css"></head>
<body>
    <div class="card">

    <!-- Formulario de edición del vehículo -->
        <h2>Editando: <?= htmlspecialchars($coche['marca_auto'] . ' ' . $coche['modelo_auto']) ?></h2>
        
        <form action="actualizar_vehiculo.php" method="post" enctype="multipart/form-data">
            
            <input type="hidden" name="id_auto" value="<?= htmlspecialchars($coche['id_auto']) ?>">

            <label for="marca_auto">Marca *</label>
            <input id="marca_auto" name="marca_auto" type="text" value="<?= htmlspecialchars($coche['marca_auto']) ?>" required>
            
            <label for="modelo_auto">Modelo *</label>
            <input id="modelo_auto" name="modelo_auto" type="text" value="<?= htmlspecialchars($coche['modelo_auto']) ?>" required>
            
            <label for="anio_auto">Año *</label>
            <input id="anio_auto" name="anio_auto" type="number" value="<?= htmlspecialchars($coche['anio_auto']) ?>" required>
            
            <label for="precio_auto">Precio *</label>
            <input id="precio_auto" name="precio_auto" type="text" value="<?= htmlspecialchars($coche['precio_auto']) ?>" required>

            <label for="descripcion_auto">Descripción</label>
            <textarea id="descripcion_auto" name="descripcion_auto" rows="4"><?= htmlspecialchars($coche['descripcion_auto']) ?></textarea>
            
            <!-- Estado del vehículo disponible o vendido -->
            <label for="estado_auto">Estado</label>
            <select id="estado_auto" name="estado_auto" required>
                <option value="disponible" <?= ($coche['estado_auto'] == 'disponible') ? 'selected' : '' ?>>Disponible</option>
                <option value="vendido" <?= ($coche['estado_auto'] == 'vendido') ? 'selected' : '' ?>>Vendido</option>
            </select>

            <hr style="margin: 20px 0;">
            
            <!-- Foto del vehículo (opcional) -->
            <label for="foto_auto">Cambiar Foto (Opcional)</label>
            <p>Foto actual:</p>
            <?php if (!empty($coche['foto_auto'])): ?>
                <img src="<?= htmlspecialchars($coche['foto_auto']) ?>" alt="Foto actual" style="width: 150px; height: auto; border-radius: 4px;">
                <input type="hidden" name="foto_actual" value="<?= htmlspecialchars($coche['foto_auto']) ?>">
            <?php else: ?>
                <p>No hay foto actual.</p>
            <?php endif; ?>
            <br>
            <input id="foto_auto" name="foto_auto" type="file" accept="image/jpeg, image/png">
            
            <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Actualizar Vehículo</button>
        </form>
        
        <a href="gestor_vehiculos.php" class="btn btn-secondary" style="margin-top: 15px;">Cancelar y Volver</a>
    </div>
</body>
</html>