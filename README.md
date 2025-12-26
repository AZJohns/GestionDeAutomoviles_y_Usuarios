# 🏎️ AutoMocion Manager - Gestión de Concesionario de Alta Gama

Este proyecto consiste en el desarrollo de una aplicación web funcional para la gestión de un concesionario de automóviles de lujo. El sistema permite administrar el inventario de vehículos exclusivos y gestionar los usuarios del sistema mediante roles diferenciados.

Proyecto realizado como parte del módulo de **Desarrollo de Aplicaciones Web**.

## 📋 Características Principales

El sistema cumple con los siguientes requerimientos funcionales:

* **Gestión de Roles de Usuario:** Implementación de un sistema de permisos con 3 niveles:
    * **Administrador:** Control total (crear/editar/borrar autos y usuarios).
    * **Empleado:** Gestión de inventario (agregar/modificar autos) sin permisos administrativos sobre usuarios.
    * **Invitado:** Acceso de solo lectura al catálogo de vehículos.
* **CRUD de Automóviles:** Funcionalidad completa para dar de alta, visualizar, editar y eliminar fichas de vehículos (Marca, Modelo, Año, Precio, Foto).
* **Seguridad:** Login seguro con validación de credenciales y almacenamiento de contraseñas encriptadas (hash).
* **Gestión de Usuarios:** El administrador puede registrar nuevos empleados y asignarles roles específicos.

## 🛠️ Tecnologías Utilizadas

* **Lenguaje:** PHP (Nativo)
* **Base de Datos:** MySQL / MariaDB
* **Frontend:** HTML5, CSS3 (Estilos personalizados para estética "Premium")
* **Servidor Local:** XAMPP / WAMP

## 🗄️ Estructura de la Base de Datos

El proyecto utiliza una base de datos relacional con las siguientes tablas principales:
1.  **`tbusuarios`**: Almacena credenciales y datos de acceso.
2.  **`tbroles`**: Define los niveles de permiso (Admin, Empleado, Invitado).
3.  **`tbautomoviles`**: Inventario de coches con detalles técnicos y precios.

## 🚀 Instalación y Despliegue

1.  Clona este repositorio.
2.  Importa el archivo `script.sql` (ubicado en la carpeta `/sql`) en tu gestor de base de datos (phpMyAdmin).
3.  Configura los parámetros de conexión en el archivo `conexion.php`.
4.  Ejecuta el proyecto desde tu servidor local (`localhost/Proyecto_Concesionario`).

---
*Desarrollado por Jonh Andrade*
