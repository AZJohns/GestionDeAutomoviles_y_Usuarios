<?php
// venta_exitosa.php
session_start();

// 1. Guardián: Solo Admin (1) o Empleado (2)
if (!isset($_SESSION['rol_id']) || ($_SESSION['rol_id'] != 1 && $_SESSION['rol_id'] != 2)) {
    die("Acceso denegado.");
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>¡Venta Completada!</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="css/style-publico.css">
</head>

<body class="admin-page">

    <header class="admin-header">
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
        <div class="admin-card" style="text-align: center;">
            <h2 style="color: #28a745;">¡Venta Registrada con Éxito!</h2>
            <p style="font-size: 1.2rem;">El vehículo ha sido marcado como "Vendido" y la transacción ha sido guardada.</p>
            <br>
            <a href="panel_admin.php" class="btn btn-primary" style="font-size: 1rem;">Volver al Panel</a>
            <a href="index.php" class="btn btn-secondary" style="font-size: 1rem;">Ver Catálogo Público</a>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Concesionario. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>