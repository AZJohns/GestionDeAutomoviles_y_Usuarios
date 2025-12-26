Proyecto desarrollado por: Jonh Sebastian Andrade Zapata


Para ingresar al panel_admin usar: user => admin contraseña => admin // solo para los desarrolladores
================================================
README: Proyecto Concesionario de Lujo (PHP/MySQL)
================================================

Este documento describe la estructura, el uso y los archivos del proyecto de gestión de concesionario web.


========================================
1. DESCRIPCIÓN DEL PROYECTO
========================================

Sistema web completo para la gestión de un concesionario de automóviles de alta gama, implementado en PHP y MySQL. El proyecto separa la vista pública (para "Invitados") de un panel de administración privado (para "Empleados" y "Administradores").

El flujo de trabajo principal es:
1.  Un "Invitado" explora el catálogo público y solicita información sobre un coche.
2.  Esta solicitud se guarda en la base de datos (`tbsolicitudes`).
3.  Un "Empleado" (Vendedor) o "Administrador" inicia sesión en el panel privado.
4.  El empleado ve la solicitud pendiente en "Ver Solicitudes".
5.  El empleado contacta al cliente y, si la venta se realiza, usa el botón "Registrar Venta" para procesar la transacción, que actualiza el inventario (`tbautomoviles`), guarda al cliente (`tbclientes`) y registra la venta (`tbventas`).


========================================
2. INSTALACIÓN
========================================

1.  **Base de Datos:**
    -   Crea una base de datos en MySQL/phpMyAdmin (`concesionario_db`).
    -   Crea las 6 tablas necesarias: `tbroles`, `tbusuarios`, `tbautomoviles`, `tbclientes`, `tbventas`, `tbsolicitudes`.
    -   Puebla la tabla `tbroles` con los roles (ej. 1: Administrador, 2: Vendedor).

2.  **Configuración:**
    -   Modifica el archivo `config_login.php` con el nombre de tu base de datos, tu usuario (ej. 'root') y tu contraseña de MySQL.

3.  **Archivos:**
    -   Coloca todos los archivos del proyecto en la carpeta de tu servidor web (En el caso de wamp `www/`).

4.  **Permisos:**
    -   Asegúrate de que la carpeta `uploads/` exista y tenga permisos de escritura que sera la carpeta donde se guarden las fotografias subidas de los vehiculos publicados.

5.  **Primer Usuario:**
    -   La tabla `tbusuarios` estará vacía. Ve a `gestor_users.php` y usa el modal "Nuevo registro" (o accede a `index_register.php`) para crear tu primer usuario.
    -   **Importante:** Asigna a este primer usuario el Rol ID `1` (Administrador).
    -   Ve a `login.php` e inicia sesión.


========================================
3. ROLES Y PERMISOS
========================================

1.  **Invitado (Público, sin sesión):**
    -   Puede ver `index.php` y `coche_detalle.php`.
    -   Puede enviar solicitudes de interés (`guardar_solicitud.php`).
    -   NO PUEDE acceder a ningún archivo del panel de admin.

2.  **Vendedor / Empleado (Rol ID = 2):**
    -   Puede iniciar sesión (`login.php`).
    -   Puede ver el `panel_admin.php` (con vista limitada).
    -   Puede ver `gestor_vehiculos.php` (pero NO ve los botones de Editar/Eliminar).
    -   Puede crear nuevos vehículos (usando el modal y `guardar_vehiculos.php`).
    -   Puede ver `ver_solicitudes.php` y "Convertir en Venta" (`registrar_venta.php`).
    -   NO PUEDE gestionar usuarios.

3.  **Administrador (Rol ID = 1):**
    -   Tiene todos los permisos del Vendedor.
    -   PUEDE gestionar usuarios (CRUD completo de `gestor_users.php`).
    -   PUEDE Editar y Eliminar vehículos (`editar_vehiculo.php`, `eliminar_vehiculo.php`).


========================================
4. DESCRIPCIÓN DE ARCHIVOS
========================================

---
[CSS y JS]
---
-   **style-publico.css:** CSS para las páginas públicas (index, coche_detalle). Estilo oscuro.
-   **style-privado.css:** CSS para todo el panel de administración (tablas, formularios, modal, etc.). Estilo limpio y funcional.
-   **style_registers.css:** (Obsoleto) Reemplazado por `style-privado.css`.
-   **gestor_users.js:** (Opcional) Lógica del modal para `gestor_users.php`.

---
[Configuración]
---
-   **config_login.php:** Conexión a la base de datos MySQL (PDO). **ÚNICO archivo a modificar para la instalación.**

---
[Flujo Público (Invitado)]
---
-   **index.php:** Página principal (Hero + Catálogo). Lee de `tbautomoviles`. Comprueba si hay sesión activa para mostrar "Hola, admin" o "Iniciar Sesión".
-   **coche_detalle.php:** Muestra los detalles de un coche específico y el formulario de interés.
-   **guardar_solicitud.php:** (Backend) Recibe datos de `coche_detalle.php` y los guarda en la tabla `tbsolicitudes`.
-   **solicitud_exitosa.php:** Página de "Gracias" después de que un invitado envía una solicitud.

---
[Autenticación (Login/Logout)]
---
-   **login.php:** Formulario de inicio de sesión. Verifica usuario contra `tbusuarios` (usando `password_verify`).
-   **logout.php:** Destruye la sesión (`session_destroy`) y redirige al login.

---
[Panel de Administración (Core)]
---
-   **panel_admin.php:** Página principal del panel. Muestra enlaces diferentes según el rol (`$_SESSION['rol_id']`).
-   **ver_solicitudes.php:** (Admin/Vendedor) Muestra la lista de `tbsolicitudes` pendientes.
-   **actualizar_solicitud.php:** (Backend) Marca solicitudes como "contactado" o "descartado".

---
[Gestión de Usuarios (CRUD de Admin)]
---
-   **gestor_users.php:** (Admin) Lista todos los usuarios. Incluye botones de Editar/Eliminar. Contiene el modal de registro.
-   **index_register.php:** (Admin) Formulario para crear un nuevo Empleado/Admin (usado por el modal de `gestor_users.php`).
-   **guardar_users.php:** (Backend) Guarda el nuevo usuario en `tbusuarios` (usando `password_hash`).
-   **editar_usuario.php:** (Admin) Formulario para editar un usuario existente (cambiar rol, contraseña).
-   **actualizar_usuario.php:** (Backend) Guarda los cambios del usuario.
-   **eliminar_usuario.php:** (Backend) Elimina un usuario.

---
[Gestión de Vehículos (CRUD de Admin/Vendedor)]
---
-   **gestor_vehiculos.php:** (Admin/Vendedor) Lista todos los vehículos. Contiene el modal de registro.
-   **register_vehiculos.php:** (DEPRECADO) Reemplazado por `formulario_vehiculo.php`.
-   **formulario_vehiculo.php:** (Recomendado) Formulario HTML simple para ser cargado en el modal de `gestor_vehiculos.php`.
-   **guardar_vehiculos.php:** (Backend) Guarda el nuevo vehículo, incluyendo la subida de la foto a la carpeta `uploads/`.
-   **editar_vehiculo.php:** (Admin) Formulario para editar un vehículo existente.
-   **actualizar_vehiculo.php:** (Backend) Guarda los cambios del vehículo (incluyendo la foto).
-   **eliminar_vehiculo.php:** (Backend) Elimina un vehículo.

---
[Gestión de Ventas (Flujo de Empleado)]
---
-   **registrar_venta.php:** (Admin/Vendedor) Formulario para registrar una venta. Muestra datos del coche y pide datos del cliente.
-   **procesar_venta.php:** (Backend) Script principal de venta.
    1. Inserta/Actualiza en `tbclientes`.
    2. Inserta en `tbventas` (registrando al empleado que vendió).
    3. Actualiza `tbautomoviles` (estado = 'vendido').
-   **venta_exitosa.php:** Página de "Venta completada" para el empleado.
```