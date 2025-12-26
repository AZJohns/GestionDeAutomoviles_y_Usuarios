<?php
// coche_detalle.php
session_start();
require 'config_login.php';

// 1. Obtener el ID del coche desde la URL
$id_auto = $_GET['id'] ?? null;
if (!$id_auto) {
    die("Error: Vehículo no especificado.");
}

// 2. Buscar los datos del coche
try {
    $sql = "SELECT * FROM tbautomoviles WHERE id_auto = ? AND estado_auto = 'disponible'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_auto]);
    $coche = $stmt->fetch();

    if ($coche === false) {
        die("Este vehículo no está disponible o no existe.");
    }
} catch (PDOException $e) {
    die("Error al consultar la base de datos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles: <?php echo htmlspecialchars($coche['marca_auto'] . ' ' . $coche['modelo_auto']); ?></title>

    <link rel="stylesheet" href="css/style-publico.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">

</head>

<body>

    <header>
        <div class="container">
            <h1 class="site-title"><a href="index.php">AutoMocion</a></h1>
            <nav>
                <ul>
                    <!-- Muestra el nombre de usuario y botones de inicio de sesión o cierre de sesión -->
                    <?php if (isset($_SESSION['rol_id'])): ?>
                        <li style="color: #aaa;">¡Hola, <?php echo htmlspecialchars($_SESSION['nom_user']); ?>!</li>
                        <li><a href="panel_admin.php" class="btn btn-secondary">Panel Admin</a></li>
                        <li><a href="logout.php" class="btn btn-primary">Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="btn btn-primary">Iniciar Sesión</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <!-- Contenido principal cambiante segun el coche -->
    <section class="detalle-hero" style="background-image: url('<?php echo htmlspecialchars($coche['foto_auto'] ?? 'img/placeholder.png'); ?>');">
        <h1><?php echo htmlspecialchars($coche['marca_auto'] . ' ' . $coche['modelo_auto']); ?></h1>
    </section>

    <main class="container">
        <div class="detalle-contenido">

            <div class="detalle-info">
                <div class="precio"><?php echo htmlspecialchars($coche['precio_auto']); ?> €</div>

                <h3>Descripción</h3>
                <p><?php echo nl2br(htmlspecialchars($coche['descripcion_auto'])); ?></p>

                <h3>Especificaciones</h3>
                <p><strong>Año:</strong> <?php echo htmlspecialchars($coche['anio_auto']); ?></p>
            </div>

            <!-- Formulario de para solicitud de interes por coche -->
            <aside class="formulario-interes">
                <h3>¿Interesado?</h3>
                <p style="font-size: 0.9rem; color: #888; margin-bottom: 1.5rem;">Un vendedor te contactará a la brevedad.</p>

                <form action="guardar_solicitud.php" method="post">
                    <input type="hidden" name="auto_id" value="<?php echo $coche['id_auto']; ?>">

                    <label for="nombre">Nombre *</label>
                    <input type="text" id="nombre" name="nombre_interesado" required>

                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email_interesado" required>

                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono_interesado">

                    <label for="mensaje">Mensaje</label>
                    <textarea id="mensaje" name="mensaje" rows="3"></textarea>

                    <button type="submit" class="btn">Enviar Solicitud</button>
                </form>
            </aside>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Concesionario de Lujo. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>