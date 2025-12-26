<?php
// ver_solicitudes.php
session_start();
require 'config_login.php';

// 1. Guardián: Solo Admin (1) o Empleado (2) pueden ver esto
if (!isset($_SESSION['rol_id']) || ($_SESSION['rol_id'] != 1 && $_SESSION['rol_id'] != 2)) {
    die("Acceso denegado.");
}

// 2. Obtener todas las solicitudes "pendientes"
// Hacemos un JOIN para obtener el nombre del coche y del cliente
try {
    $sql = "SELECT 
                s.id_solicitud, 
                s.nombre_interesado, 
                s.email_interesado, 
                s.telefono_interesado, 
                s.mensaje, 
                s.fecha_solicitud,
                a.marca_auto, 
                a.modelo_auto,
                a.id_auto
            FROM 
                tbsolicitudes s
            JOIN 
                tbautomoviles a ON s.auto_id = a.id_auto
            WHERE 
                s.estado_solicitud = 'pendiente'
            ORDER BY 
                s.fecha_solicitud ASC";
    
    $stmt = $pdo->query($sql);
    $solicitudes = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Error al consultar las solicitudes: " . $e->getMessage());
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ver Solicitudes de Clientes</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="css/style-privado.css"> 
</head>
<body class="admin-page"> <header class="admin-header">
        <div class="container"> 
            <h1 class="site-title"><a href="panel_admin.php">Panel de Gestión</a></h1>
            <nav>
                <ul>
                    <li>¡Hola, <?php echo htmlspecialchars($_SESSION['nom_user']); ?>!</li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="admin-container">
        <div class="card">
            <div class="top">
                <h2 style="margin:0;">Solicitudes Pendientes</h2>
                <a href="panel_admin.php" class="btn btn-secondary">Volver al Panel</a>
            </div>

            <?php if (count($solicitudes) === 0): ?>
                <p style="margin-top: 2rem; font-size: 1.1rem; text-align: center;">No hay solicitudes pendientes en este momento.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Contacto</th>
                            <th>Vehículo Interesado</th>
                            <th>Mensaje</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $s): ?>
                            <tr>
                                <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($s['fecha_solicitud']))) ?></td>
                                <td><?= htmlspecialchars($s['nombre_interesado']) ?></td>
                                <td>
                                    <?= htmlspecialchars($s['email_interesado']) ?><br>
                                    <?= htmlspecialchars($s['telefono_interesado']) ?>
                                </td>
                                <td><?= htmlspecialchars($s['marca_auto'] . ' ' . $s['modelo_auto']) ?></td>
                                <td><?= htmlspecialchars($s['mensaje']) ?></td>
                                <td>
                                    <a href="registrar_venta.php?id=<?= $s['id_auto'] ?>" class="btn btn-primary" style="margin-bottom: 5px; width: 100%; text-align: center;">
                                        Convertir en Venta
                                    </a>
                                    
                                    <a href="actualizar_solicitud.php?id=<?= $s['id_solicitud'] ?>&estado=contactado" class="btn btn-secondary" style="margin-bottom: 5px; width: 100%; text-align: center;">
                                        Marcar Contactado
                                    </a>
                                    <a href="actualizar_solicitud.php?id=<?= $s['id_solicitud'] ?>&estado=descartado" class="btn btn-danger" style="width: 100%; text-align: center;" onclick="return confirm('¿Descartar esta solicitud?');">
                                        Descartar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Concesionario. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>