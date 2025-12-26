<?php
// --- 1. Iniciar la sesión ---
session_start();

// --- 2. Incluir la conexión a la BD ---
require 'config_login.php';

$error_message = "";

// --- 3. Comprobar si el formulario fue enviado ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- 4. Recoger los datos del formulario ---
    $user = $_POST["user"] ?? '';
    $pass = $_POST["pass"] ?? '';

    if (empty($user) || empty($pass)) {
        $error_message = "Por favor, rellena ambos campos.";
    } else {

        // --- 5. La consulta SQL ---
        $sql = "SELECT * FROM tbusuarios WHERE nom_user = ?";

        // 6. Preparamos la consulta
        $stmt = $pdo->prepare($sql);

        // 7. Ejecutamos
        $stmt->execute([$user]);

        // 8. Obtenemos el resultado
        $usuario_encontrado = $stmt->fetch();

        // --- 9. Verificación del Usuario ---
        if ($usuario_encontrado === false) {
            $error_message = "Error: El usuario no existe.";
        } else {

            // --- 10. MODIFICACIÓN: Verificación de Contraseña en TEXTO PLANO ---
            // Obtenemos la contraseña (plana) de la BD
            $pass_de_la_bd = $usuario_encontrado['pass_user'];

            // Comparamos directamente la contraseña del formulario ($pass)
            // con la contraseña de la base de datos ($pass_de_la_bd)
            if (password_verify($pass, $pass_de_la_bd)) {
                // ¡El usuario y la contraseña son correctos!

                // 11. Guardamos los datos en la sesión
                session_regenerate_id(true); // Genera un nuevo ID de sesión
                $_SESSION['id_usuario'] = $usuario_encontrado['id_usuario'];
                $_SESSION['nom_user']   = $usuario_encontrado['nom_user'];
                $_SESSION['rol_id']     = $usuario_encontrado['rol_id'];

                // 12. Redirigimos al panel de control
                header('Location:panel_admin.php');
                exit;
            } else {

                // Error: Contraseña incorrecta
                $error_message = "Error: La contraseña es incorrecta.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gestión Autos</title>
    <link rel="stylesheet" href="css/style_login.css">
</head>

<body>
    <header class="site-header">
        <div class="container">
            <h1 class="site-title"><a href="index.php">AutoMocion</a></h1>
        </div>
    </header>

    <main class="page-content">

        <div class="card" style="max-width:420px;margin:0 auto">
            <h2>Iniciar sesión</h2>

            <form action="login.php" method="post">

                <?php
                // --- Mostrar el mensaje de error si existe ---
                if (!empty($error_message)) {
                    // Usamos un div con un estilo simple para el error
                    echo '<div style="color: red; background: #ffebee; border: 1px solid red; padding: 10px; margin-bottom: 10px; border-radius: 4px;">' . htmlspecialchars($error_message) . '</div>';
                }
                ?>

                <div style="margin-bottom:10px">
                    <label for="user">Usuario</label><br>
                    <input type="text" id="user" name="user" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px" required>
                </div>
                <div style="margin-bottom:10px">
                    <label for="pass">Contraseña</label><br>
                    <input type="password" id="pass" name="pass" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:4px" required>
                </div>
                <button type="submit" style="background:var(--primary);color:black;padding:10px 14px;;border-radius:4px;cursor:pointer">Entrar</button>
            </form>
        </div>

    </main>

    <footer>
        <div class="logo">
            <p>&copy; 2025 Banco JZ. Todos los derechos reservados.</p>
            <img src="img/logo-bank.png" alt="">
        </div>
    </footer>
</body>

</html>