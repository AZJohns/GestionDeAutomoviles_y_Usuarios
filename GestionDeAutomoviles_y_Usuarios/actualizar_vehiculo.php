<?php
// actualizar_vehiculo.php
session_start();
require 'config_login.php';

// 1. Guardián: Solo Admin (1) o Empleado (2)
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    // Si no es Admin, lo expulsa
    die("Acceso denegado. No tienes permisos para realizar esta acción.");
}

// 2. Comprobar que los datos llegan por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Recoger todos los datos del formulario
    $id_auto = $_POST['id_auto'];
    $marca_auto = trim($_POST['marca_auto']);
    $modelo_auto = trim($_POST['modelo_auto']);
    $anio_auto = trim($_POST['anio_auto']);
    $precio_auto = trim($_POST['precio_auto']);
    $descripcion_auto = trim($_POST['descripcion_auto']);
    $estado_auto = trim($_POST['estado_auto']);
    $foto_actual = $_POST['foto_actual'] ?? null; // La ruta de la foto que ya estaba

    $ruta_foto_bd = $foto_actual; // Por defecto, mantenemos la foto actual

    // 4. LÓGICA DE LA FOTO: ¿Se subió una NUEVA foto?
    if (isset($_FILES['foto_auto']) && $_FILES['foto_auto']['error'] === UPLOAD_ERR_OK) {

        // Sí, se subió una nueva. Procesémosla.
        $file_tmp_path = $_FILES['foto_auto']['tmp_name'];
        $file_name = $_FILES['foto_auto']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = uniqid('', true) . '.' . $file_ext;
            $upload_dir = 'uploads/';
            $dest_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp_path, $dest_path)) {
                $ruta_foto_bd = $dest_path; // Usamos la NUEVA ruta

                // Opcional: Borrar la foto antigua si existía
                if ($foto_actual && file_exists($foto_actual)) {
                    unlink($foto_actual);
                }
            } else {
                die("Error al mover la nueva foto.");
            }
        } else {
            die("Tipo de archivo no permitido.");
        }
    }

    // 5. Preparamos la consulta UPDATE
    try {
        $sql = "UPDATE tbautomoviles
                SET marca_auto = ?,
                    modelo_auto = ?,
                    anio_auto = ?,
                    precio_auto = ?,
                    descripcion_auto = ?,
                    foto_auto = ?,
                    estado_auto = ?
                WHERE id_auto = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $marca_auto,
            $modelo_auto,
            $anio_auto,
            $precio_auto,
            $descripcion_auto,
            $ruta_foto_bd, // La ruta de la foto (nueva o la antigua)
            $estado_auto,
            $id_auto // El ID del coche a actualizar
        ]);

        // 6. Redirigir de vuelta al gestor
        header('Location: gestor_vehiculos.php?msg=actualizado');
        exit;
    } catch (PDOException $e) {
        die("Error al actualizar el vehículo: " . $e->getMessage());
    }
} else {
    header('Location: gestor_vehiculos.php');
    exit;
}
