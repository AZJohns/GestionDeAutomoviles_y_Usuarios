<?php
// panel_admin.php
session_start();

// 1. Comprobar si el usuario ha iniciado sesión y tiene el rol autorizado
if (!isset($_SESSION['rol_id']) || ($_SESSION['rol_id'] != 1 && $_SESSION['rol_id'] != 2)) {
    // Si no es admin (1) NI empleado (2), lo expulsamos
    header('Location: login.php');
    exit;
}

$nombre_usuario = $_SESSION['nom_user'];
$rol_usuario = $_SESSION['rol_id'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>

    <link rel="stylesheet" href="css/style-privado.css">
</head>

<body class="admin-page">

    <header class="admin-header">
        <div class="container">
            <h1 class="site-title"><a href="panel_admin.php">Panel de Gestión</a></h1>
            <nav>
                <ul>
                    <!-- Para enseñar el nombre del usuario  -->
                    <li>¡Hola, <?php echo htmlspecialchars($nombre_usuario); ?>!</li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="admin-container">

        <h2>Bienvenido al panel</h2>
        <p>Aquí podrás gestionar el concesionario.</p>

        <div class="admin-card">
            <?php
            // Si el rol es 1 (Administrador) 
            // Enseñamos las opciones para admin
            if ($rol_usuario == 1) {
                echo '<h3>Opciones de Administrador</h3>';
                //  Contenedor para organizar los enlaces
                echo '<div class="admin-options">';
                echo '<a href="gestor_users.php">Gestionar Empleados</a>';
                echo '<a href="gestor_vehiculos.php">Gestionar Automóviles</a>';

                echo '<a href="ver_solicitudes.php">Ver Solicitudes de Clientes</a>';

                echo '<a href="index.php">Ver Pagina de Vehiculos</a>';

                echo '</div>';
            }
            // Si el rol es 2 (Empleado/Vendedor)
            // Enseñamos las opciones para empleado

            else if ($rol_usuario == 2) {
                // Contenedor para organizar los enlaces.
                echo '<h3>Opciones de Empleado</h3>';
                echo '<div class="admin-options">';
                echo '<a href="gestor_vehiculos.php">Gestionar Automóviles</a>';
                echo '<a href="ver_solicitudes.php">Ver Solicitudes de Clientes</a>';

                echo '<a href="index.php">Ver Pagina de Vehiculos</a>';

                echo '</div>';
            }
            ?>
        </div>

    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Concesionario. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>