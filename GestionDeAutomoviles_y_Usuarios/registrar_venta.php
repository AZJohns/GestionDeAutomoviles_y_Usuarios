<?php
// registrar_venta.php
session_start();
require 'config_login.php';

// 1. Guardián: Solo Admin (1) o Empleado (2) pueden registrar ventas
if (!isset($_SESSION['rol_id']) || ($_SESSION['rol_id'] != 1 && $_SESSION['rol_id'] != 2)) {
    die("Acceso denegado. No tienes autorización para registrar ventas.");
}

// 2. Obtener ID del vehículo desde la URL
$id_auto = $_GET['id'] ?? null;
if (!$id_auto) {
    die("Error: No se especificó ningún vehículo.");
}

// 3. Buscar los datos del vehículo
try {
    $sql = "SELECT * FROM tbautomoviles WHERE id_auto = ? AND estado_auto = 'disponible'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_auto]);
    $coche = $stmt->fetch();

    if ($coche === false) {
        die("Vehículo no encontrado o ya vendido.");
    }
} catch (PDOException $e) {
    die("Error al consultar la base de datos: " . $e->getMessage());
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Registrar Venta</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="css/style-privado.css">
</head>

<body class="admin-page">

    <header class="admin-header">
        <div class="container">
            <h1 class="site-title"><a href="panel_admin.php">Panel de Gestión</a></h1>
            <nav>
                <ul>
                    <!-- Muestra el nombre de usuario y botones de inicio de sesión o cierre de sesión -->
                    <li>¡Hola, <?php echo htmlspecialchars($_SESSION['nom_user']); ?>!</li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="admin-container">
        <h2>Registrar Venta</h2>

        <div class="admin-card">
            <h3>Vehículo a Vender</h3>
            <div style="display: flex; gap: 20px;">
                <!-- Mostrar la foto del vehículo -->
                <?php if (!empty($coche['foto_auto'])): ?>
                    <img src="<?= htmlspecialchars($coche['foto_auto']) ?>" alt="Foto" style="width: 200px; height: auto; border-radius: 4px;">
                <?php endif; ?>
                <div>
                    <!-- Mostrar los detalles del vehiculo -->
                    <h4><?= htmlspecialchars($coche['marca_auto'] . ' ' . $coche['modelo_auto']) ?></h4>
                    <p><strong>Año:</strong> <?= htmlspecialchars($coche['anio_auto']) ?></p>
                    <p><strong>Descripción:</strong> <?= htmlspecialchars($coche['descripcion_auto']) ?></p>
                    <p style="font-size: 1.5rem; color: #007bff; font-weight: 700;">
                        Precio Base: <?= htmlspecialchars($coche['precio_auto']) ?> €
                    </p>
                </div>
            </div>
        </div>

        <div class="admin-card" style="margin-top: 2rem;">
            <h3>Datos del Cliente</h3>
            <!-- Formulario para registrar la venta con datos del cliente -->
            <form action="procesar_venta.php" method="post">
                <input type="hidden" name="auto_id" value="<?= $coche['id_auto'] ?>">
                <input type="hidden" name="usuario_id" value="<?= $_SESSION['id_usuario'] ?>">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label for="dni_cli">DNI Cliente *</label>
                        <input type="text" id="dni_cli" name="dni_cli" required style="width:100%;">
                    </div>
                    <div>
                        <label for="email_cli">Email Cliente *</label>
                        <input type="email" id="email_cli" name="email_cli" required style="width:100%;">
                    </div>
                    <div>
                        <label for="nombre_cli">Nombre Cliente *</label>
                        <input type="text" id="nombre_cli" name="nombre_cli" required style="width:100%;">
                    </div>
                    <div>
                        <label for="apellido_cli">Apellido Cliente *</label>
                        <input type="text" id="apellido_cli" name="apellido_cli" required style="width:100%;">
                    </div>
                    <div>
                        <label for="telefono_cli">Teléfono</label>
                        <input type="text" id="telefono_cli" name="telefono_cli" style="width:100%;">
                    </div>
                    <div>
                        <label for="direccion_cli">Dirección</label>
                        <input type="text" id="direccion_cli" name="direccion_cli" style="width:100%;">
                    </div>
                </div>

                <hr style="margin: 20px 0;">

                <div>
                    <label for="precio_final" style="font-size: 1.2rem; font-weight: 700;">Precio Final de Venta (€) *</label>
                    <input type="number" step="0.01" id="precio_final" name="precio_final"
                        value="<?= htmlspecialchars($coche['precio_auto']) ?>" required
                        style="width: 100%; font-size: 1.5rem; padding: 10px;">
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top: 20px; padding: 15px; font-size: 1.2rem;">
                    Confirmar y Registrar Venta
                </button>
            </form>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Concesionario. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>