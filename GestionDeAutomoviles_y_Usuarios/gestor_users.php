<?php
// gestor_users.php

// --- 1. Iniciar la sesión ---
session_start();

// --- 2. Incluir la conexión a la BD ---
require 'config_login.php';

// --- 3. Comprobar si el usuario es admin ---
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    header('Location: login.php');
    exit;
}

// --- 4. Obtener parámetro de búsqueda ---
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

// --- 5. LÓGICA DE BÚSQUEDA CORREGIDA (PARTE 1) ---
// (Este es el código que tenías en tu imagen)
if ($q !== '') {
    // Usamos 'u' como alias para tbusuarios y 'r' para tbroles
    $sql = "SELECT u.*, r.nom_rol 
            FROM tbusuarios u
            JOIN tbroles r ON u.rol_id = r.id_rol
            WHERE u.nom_user LIKE ? 
               OR u.email_user LIKE ? 
               OR r.nom_rol LIKE ?
            ORDER BY u.id_usuario ASC";

    $stmt = $pdo->prepare($sql);
    $search_term = "%" . $q . "%";
    $stmt->execute([$search_term, $search_term, $search_term]);
} else {
    // TAMBIÉN AÑADIR EL JOIN AQUÍ
    $sql = "SELECT u.*, r.nom_rol 
            FROM tbusuarios u
            JOIN tbroles r ON u.rol_id = r.id_rol
            ORDER BY u.id_usuario ASC";
    $stmt = $pdo->query($sql);
}
// --- 6. Obtener resultados ---
$lista_usuarios = $stmt->fetchAll();
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Gestión de Empleados</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="css/style_registers.css">
</head>

<body>
    <div class="card">
        <div class="top">
            <h2 style="margin:0;">Empleados registrados</h2>
            <div style="margin-left:auto;">
                <button id="btnNuevo" class="btn btn-primary">Nuevo registro</button>
                <a href="panel_admin.php">
                    <button class="btn btn-primary">Volver al menú admin</button>
                </a>
            </div>
        </div>

        <!-- Formulario de búsqueda -->
        <form method="get" style="margin-top:10px;">
            <input type="search" name="q" placeholder="Buscar por nombre, email o rol"
                value="<?php echo htmlspecialchars($q); ?>">
            <button type="submit" class="btn">Buscar</button>
            <a href="gestor_users.php" class="btn">Mostrar todo</a>
        </form>

        <?php if (count($lista_usuarios) === 0): ?>
            <p>No hay registros.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_usuarios as $m): ?>
                        <tr>
                            <!-- Mostrar los datos -->
                            <td><?= htmlspecialchars($m['id_usuario']) ?></td>

                            <td><?= htmlspecialchars($m['nom_user']) ?></td>

                            <td><?= htmlspecialchars($m['email_user']) ?></td>

                            <td><?= htmlspecialchars($m['nom_rol']) ?></td>


                            <!-- Botones de edición y eliminación -->
                            <td>
                                <a href="editar_usuario.php?id=<?= $m['id_usuario'] ?>" class="btn">Editar</a>
                                <a href="eliminar_usuario.php?id=<?= $m['id_usuario'] ?>"
                                    class="btn btn-danger"
                                    onclick="return confirm('¿Estás seguro de que quieres eliminar a este usuario?');">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Modal reutilizado -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="cerrar">&times;</span>
            <div id="modal-body">
            </div>
        </div>
    </div>

    <!-- Código JavaScript -->

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("modal");
            const cerrar = document.querySelector(".cerrar");
            const btnNuevo = document.getElementById("btnNuevo");
            const modalBody = document.getElementById("modal-body");

            // Abrir modal y cargar el formulario por AJAX
            btnNuevo.addEventListener("click", async () => {
                try {
                    const res = await fetch("index_register.php");
                    if (!res.ok) throw new Error("No se pudo cargar el formulario");
                    const html = await res.text();
                    modalBody.innerHTML = html;
                    modal.style.display = "block";

                    // Quitar encabezado redundante si se desea (opcional)
                    const h2 = modalBody.querySelector("h2");
                    if (h2) h2.remove();

                    // Prevenir redirección en el formulario cargado
                    const form = modalBody.querySelector("form");
                    form.addEventListener("submit", async (e) => {
                        e.preventDefault();
                        const datos = new FormData(form);

                        try {
                            const resp = await fetch("guardar_users.php", {
                                method: "POST",
                                body: datos
                            });
                            if (!resp.ok) throw new Error("Error al guardar");

                            alert("Empleado registrado correctamente.");
                            modal.style.display = "none";
                            location.reload();
                        } catch (error) {
                            alert("Error: " + error.message);
                        }
                    });

                } catch (error) {
                    alert("Error al cargar el formulario: " + error.message);
                }
            });

            // Cerrar modal al hacer clic en la X o fuera
            cerrar.addEventListener("click", () => modal.style.display = "none");
            window.addEventListener("click", (e) => {
                if (e.target === modal) modal.style.display = "none";
            });
        });
    </script>
</body>

</html>