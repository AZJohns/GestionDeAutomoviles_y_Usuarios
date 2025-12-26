<?php
// actualizar_usuario.php
session_start();
require 'config_login.php';

// 1. Guardián: Solo Admin
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    die("Acceso denegado.");
}

// 2. Comprobar que los datos llegan por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Recoger todos los datos del formulario
    $id_usuario = $_POST['id_usuario'];
    $nom_user   = trim($_POST['nom_user']);
    $email_user = trim($_POST['email_user']);
    $rol_id     = $_POST['rol_id'];
    $pass       = $_POST['pass_user'];
    $confirm_pass = $_POST['confirm_pass_user'];

    // 4. LÓGICA DE LA CONTRASEÑA
    $parametros_sql = [
        ':nom_user'   => $nom_user,
        ':email_user' => $email_user,
        ':rol_id'     => $rol_id,
        ':id_usuario' => $id_usuario
    ];

    $sql_update_pass = ""; // Por defecto, no actualizamos la contraseña

    // Si el campo de nueva contraseña NO está vacío
    if (!empty($pass)) {
        // Comprobamos si coinciden
        if ($pass !== $confirm_pass) {
            die("Error: Las nuevas contraseñas no coinciden. <a href='editar_usuario.php?id=$id_usuario'>Volver</a>");
        }

        //  Hasheamos la nueva contraseña
        $pass_hasheada = password_hash($pass, PASSWORD_DEFAULT);


        // Añadimos la contraseña a la consulta SQL
        $sql_update_pass = ", pass_user = :pass_user";
        $parametros_sql[':pass_user'] = $pass_hasheada;
    }

    // 5. Preparamos la consulta UPDATE
    try {
        $sql = "UPDATE tbusuarios 
                SET nom_user = :nom_user, 
                    email_user = :email_user, 
                    rol_id = :rol_id
                    $sql_update_pass 
                WHERE id_usuario = :id_usuario";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($parametros_sql);

        // 6. Redirigir de vuelta al gestor
        header('Location: gestor_users.php');
        exit;
    } catch (PDOException $e) {
        die("Error al actualizar el usuario: " . $e->getMessage());
    }
} else {
    header('Location: gestor_users.php');
    exit;
}
