# CRUD_FETCH_MYSQL
Sistema web desarrollado como parte de un laboratorio académico, enfocado en la gestión de productos utilizando PHP, MySQL y Fetch API. El proyecto integra arquitectura en capas, programación orientada a objetos y una interfaz moderna y personalizada.
## Descripción
Este proyecto consiste en una aplicación web para administrar un listado de productos mediante operaciones CRUD completas.
Está construido con un enfoque modular:
- Frontend dinámico con Fetch API
- Backend en PHP con POO
- Base de datos MySQL con consultas seguras
El objetivo principal es practicar el envío y recepción de datos en formato JSON y trabajar con interfaces fluidas sin recargar la página.
## Características
- **CRUD Funcional:** guardar, editar, eliminar y listar productos
- **Diseño personalizado:** interfaz con estilos en tonos morados y estructura basada en Bootstrap
- **Actualización sin recargar:** Fetch API para una experiencia fluida
- **Código organizado:** backend separado por clases (DB y Producto)
- **Búsqueda avanzada:** filtrado de productos mediante coincidencias por nombre
- **Alertas modernas:** SweetAlert2 para confirmaciones y errores
- **Validaciones completas:** lado cliente y servidor
## Tecnologías Utilizadas
### Frontend
- HTML5
- CSS3 (estilos personalizados)
- JavaScript ES6
- Bootstrap 5
- SweetAlert2
### Backend
- PHP 7+
- Programación Orientada a Objetos
- PDO para conexión segura a MySQL
### Herramientas
- Fetch API
- Git / GitHub
- XAMPP
### Instalación
1. Clonar el repositorio
2. Configurar servidor web (XAMPP/WAMP)
3. Importar base de datos crud_fetch_mysql.sql
4. Configurar conexión en Modelo/conexion.php
## Funcionalidades
- Registrar nuevos productos
- Editar productos existentes con carga automática
- Buscar artículos por nombre con coincidencias parciales
- Listado dinámico que responde al servidor
- Eliminación con confirmación interactiva
- Botón para volver a mostrar todo el listado
- Validaciones en cliente + validaciones en servidor
## Autores
- Estudiantes: Stephany Chong y Alexandra Zheng
- Asignatura: Ingeniería Web
- Universidad Tecnológica de Panamá
- Docente: Ing. Irina Fong
