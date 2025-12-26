<?php
// solicitud_exitosa.php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud Enviada</title>
    <link rel="stylesheet" href="css/style-publico.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body style="display: flex; flex-direction: column; min-height: 100vh;">

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

    <!-- Contenido principal -->
    <main class="container" style="flex-grow: 1; display: flex; align-items: center; justify-content: center; padding: 4rem 0;">
        <div style="background: #1a1a1a; padding: 3rem; border-radius: 5px; text-align: center; border: 1px solid #2a2a2a;">
            <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; color: #fff;">¡Gracias por tu interés!</h2>
            <p style="color: #aaa; font-size: 1.2rem; margin-top: 1rem;">Hemos recibido tu solicitud.</p>
            <p style="color: #aaa; margin-bottom: 2rem;">Uno de nuestros vendedores se pondrá en contacto contigo a la brevedad.</p>
            <a href="index.php" class="btn" style="background: #fff; color: #000; padding: 12px 24px;">Volver al Catálogo</a>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Concesionario de Lujo. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>