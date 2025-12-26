<?php
// procesar_venta.php
session_start();
require 'config_login.php'; // Tu conexión $pdo

// 1. Guardián de seguridad (Rol 1 o 2)
if (!isset($_SESSION['rol_id']) || ($_SESSION['rol_id'] != 1 && $_SESSION['rol_id'] != 2)) {
    // Si no es admin (1) NI empleado (2), lo expulsamos
    header('Location: login.php');
    exit;
}

// 2. Comprobar que los datos llegan por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Recoger TODOS los datos del formulario
    $auto_id      = $_POST['auto_id'];
    $usuario_id   = $_POST['usuario_id']; // ID del empleado que vende
    $precio_final = $_POST['precio_final'];

    // Datos del cliente
    $dni_cli      = trim($_POST['dni_cli']);
    $email_cli    = trim($_POST['email_cli']);
    $nombre_cli   = trim($_POST['nombre_cli']);
    $apellido_cli = trim($_POST['apellido_cli']);
    $telefono_cli = trim($_POST['telefono_cli']);
    $direccion_cli = trim($_POST['direccion_cli']);


    $id_cliente_final = null;

   // 5. Procesar la venta
    try {
        $pdo->beginTransaction();

        //  MANEJAR AL CLIENTE (Tabla: tbclientes)
        // Primero, buscamos si el cliente ya existe por su DNI
        $sql_check = "SELECT id_cliente FROM tbclientes WHERE dni_cli = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$dni_cli]);
        $cliente_existente = $stmt_check->fetch();

        if ($cliente_existente) {
            // Si existe, usamos su ID
            $id_cliente_final = $cliente_existente['id_cliente'];

        } else {
            // Si NO existe, lo insertamos
            $sql_insert_cli = "INSERT INTO tbclientes
                                (nombre_cli, apellido_cli, dni_cli, email_cli, telefono_cli, direccion_cli)
                                VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_insert_cli = $pdo->prepare($sql_insert_cli);
            $stmt_insert_cli->execute([
                $nombre_cli,
                $apellido_cli,
                $dni_cli,
                $email_cli,
                $telefono_cli,
                $direccion_cli
            ]);

            // Obtenemos el ID del cliente que acabamos de crear
            $id_cliente_final = $pdo->lastInsertId();
        }

        // REGISTRAR LA VENTA (Tabla: tbventas)
        // La fecha (fecha_venta) se inserta automáticamente (CURRENT_TIMESTAMP por defecto)
        $sql_venta = "INSERT INTO tbventas (auto_id, cliente_id, usuario_id, precio_final) 
                      VALUES (?, ?, ?, ?)";
        $stmt_venta = $pdo->prepare($sql_venta);
        $stmt_venta->execute([
            $auto_id,
            $id_cliente_final,
            $usuario_id,
            $precio_final
        ]);

        // 6. ACTUALIZAR EL INVENTARIO (Tabla: tbautomoviles)
        // Marcamos el coche como 'vendido'
        $sql_update_auto = "UPDATE tbautomoviles SET estado_auto = 'vendido' WHERE id_auto = ?";
        $stmt_update_auto = $pdo->prepare($sql_update_auto);
        $stmt_update_auto->execute([$auto_id]);

        // 7. Si todo fue bien, confirmamos la transacción
        $pdo->commit();

        // 8. Redirigir a la página de éxito
        header('Location: venta_exitosa.php');
        exit;
    } catch (PDOException $e) {
        // 9. Si algo falló, revertimos todo
        $pdo->rollBack();
        die("Error al procesar la venta: " . $e->getMessage());
    }
} else {
    // Si no es POST, redirigir
    header('Location: index.php');
    exit;
}
