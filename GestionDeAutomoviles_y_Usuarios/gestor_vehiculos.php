<?php
// gestor_vehiculos.php

//iniciar seccion
session_start();

// Incluir la conexión a la BD
require 'config_login.php';

// Comprobar si el usuario es admin
// POR ESTO (para permitir Rol 1 y 2):
if (!isset($_SESSION['rol_id']) || ($_SESSION['rol_id'] != 1 && $_SESSION['rol_id'] != 2)) {
    // Si no es admin (1) NI empleado (2), lo expulsamos
    header('Location: login.php');
    exit;
}

// 2. Obtener parámetro de búsqueda
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

// --- 3. Lógica de Búsqueda Corregida ---
if ($q !== '') {
    // 3a. Buscamos con LIKE en las 4 columnas
    $sql = "SELECT * FROM tbautomoviles 
            WHERE marca_auto LIKE ? OR modelo_auto LIKE ? OR anio_auto LIKE ?  OR precio_auto LIKE ?";


    $stmt = $pdo->prepare($sql);

    // 3b. Preparamos el término de búsqueda para LIKE
    $search_term = "%" . $q . "%";

    // 3c. Ejecutamos pasando el término a los 4 marcadores ?
    $stmt->execute([$search_term, $search_term, $search_term, $search_term]);
} else {
    // 3d. Corregido: Usamos id_auto
    $sql = "SELECT * FROM tbautomoviles ORDER BY id_auto ASC";
    $stmt = $pdo->query($sql);
}

// 4. Obtenemos todos los resultados
$lista_vehiculos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Listado de coches</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="css/style-privado.css">
</head>

<body>
    <div class="card">
        <div class="top">
            <h2 style="margin:0;">Coches publicados</h2>
            <div style="margin-left:auto;">
                <a href="register_vehiculos.php">
                    <button id="btnNuevo" class="btn btn-primary">Nuevo registro</button>
                </a>
                <a href="panel_admin.php">
                    <button class="btn btn-primary" style="margin-top: 5px">Volver al menú admin</button>
                </a>
            </div>
        </div>

        <form method="get" style="margin-top:10px;">
        </form>
        <!-- mostramos la Tabla -->
        <?php if (count($lista_vehiculos) === 0): ?> <p>No hay registros.</p>
        <?php else: ?>
            <table>
                <thead>
                    <!-- Cabeza de la Tabla -->
                    <tr>
                        <th>ID</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Año</th>
                        <th>Precio</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Cuerpo de la Tabla -->
                    <?php foreach ($lista_vehiculos as $m): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($m['id_auto']); ?></td>
                            <td><?php echo htmlspecialchars($m['marca_auto']); ?></td>
                            <td><?php echo htmlspecialchars($m['modelo_auto']); ?></td>
                            <td><?php echo htmlspecialchars($m['anio_auto']); ?></td>
                            <td><?php echo htmlspecialchars($m['precio_auto']); ?></td>
                            <td class="col-descripcion"><?php echo htmlspecialchars($m['descripcion_auto']); ?></td>


                            <td>
                                <a href="registrar_venta.php?id=<?= $m['id_auto'] ?>" class="btn btn-success" style="margin-bottom: 1px;">
                                    Vender
                                </a>
                                <?php
                                // Comprobamos si el usuario logueado es Admin (Rol 1)
                                if ($_SESSION['rol_id'] == 1):

                                ?>
                                    <!-- Botones de edición y eliminación -->
                                    <a href="editar_vehiculo.php?id=<?= $m['id_auto'] ?>" class="btn btn-primary">Editar</a>
                                    <a href="eliminar_vehiculo.php?id=<?= $m['id_auto'] ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro?');">
                                        Eliminar
                                    </a>
                                <?php else: ?>
                                    <span>---</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Mostramos la foto -->
                                <?php if (!empty($m['foto_auto'])): ?>
                                    <img src="<?php echo htmlspecialchars($m['foto_auto']); ?>" alt="Foto" style="width: 100px; height: auto;">
                                <?php else: ?>
                                    Sin foto
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</body>

</html>