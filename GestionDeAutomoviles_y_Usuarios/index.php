<?php
// index.php

// iniciar seccion
session_start();

// Incluir la conexion a la BD
require 'config_login.php';

// Obtener coches disponibles
$sql = "SELECT * FROM tbautomoviles WHERE estado_auto = 'disponible' ORDER BY id_auto DESC";


$stmt = $pdo->query($sql);
// Obtener los coches
$coches = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Autos - Lujo y Rendimiento</title>

    <link rel="stylesheet" href="css/style-publico.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@700&display=swap" rel="stylesheet">
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

    <section class="hero">
        <div class="hero-content">
            <h2>El Coche de Tus Sueños</h2>
            <p>Descubre nuestra exclusiva selección de vehículos de alta gama.</p>
            <a href="#catalogo" class="btn">Descubrir Modelos</a>
        </div>
    </section>

    <main class="catalog-section" id="catalogo">
        <div class="container">
            <h3>Nuestra Colección</h3>
            <div class="coches-grid">

            <!-- Si no hay coches disponibles -->
                <?php if (count($coches) === 0): ?>
                    <p>No hay vehículos disponibles en este momento.</p>
                <?php else: ?>
                    <!-- Mostrar los coches -->
                    <?php foreach ($coches as $coche): ?>
                        <article class="card-coche">
                            <!-- Mostrar la foto del coche -->
                            <?php if (!empty($coche['foto_auto'])): ?>
                                <img src="<?php echo htmlspecialchars($coche['foto_auto']); ?>" alt="<?php echo htmlspecialchars($coche['marca_auto']); ?>">
                            <?php else: ?>
                                <!-- Si no hay foto, mostrar una imagen de relleno -->
                                <img src="img/placeholder.png" alt="Sin foto">
                            <?php endif; ?>

                            <!-- Mostrar los detalles del coche -->
                            <div class="card-coche-content">
                                <h4><?php echo htmlspecialchars($coche['marca_auto'] . ' ' . $coche['modelo_auto']); ?></h4>
                                <p>Año: <?php echo htmlspecialchars($coche['anio_auto']); ?></p>
                                <p><?php echo htmlspecialchars($coche['descripcion_auto']); ?></p>
                                <p class="precio"><?php echo htmlspecialchars($coche['precio_auto']); ?> €</p>
                            </div>

                            <a href="coche_detalle.php?id=<?php echo $coche['id_auto']; ?>" class="btn-details">Ver detalles</a>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Concesionario de Lujo. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>