<?php
// Este archivo recibe todas las peticiones AJAX (fetch)
// y responde siempre con un JSON para que JS pueda trabajar con él.

header("Content-Type: application/json; charset=utf-8");

require_once 'Modelo/productos.php';

// Estructura base que usaremos en todas las respuestas
$respuesta = [
    'success' => false, // indica si la operación fue ok
    'message' => '',    // mensaje para el usuario
    'accion'  => '',    // acción que se intentó ejecutar
    'data'    => null,  // datos que devolvemos (si aplica)
    'errors'  => []     // errores de validación
];

try {
    // Leemos la acción enviada desde JavaScript
    $accion = $_POST['accion'] ?? '';

    // Creamos un objeto Producto para trabajar con sus métodos
    $producto = new Producto();

    // Pasamos los datos del formulario al objeto
    $producto->id       = $_POST['id']       ?? null;
    $producto->codigo   = trim($_POST['codigo']   ?? '');
    $producto->producto = trim($_POST['producto'] ?? '');
    $producto->precio   = $_POST['precio']   ?? '';
    $producto->cantidad = $_POST['cantidad'] ?? '';

    // Dependiendo de la acción, se ejecuta una parte del CRUD
    switch ($accion) {

        // Guardar un nuevo producto
        case 'guardar':
            // Validamos los datos primero
            $errores = $producto->validar();
            if (!empty($errores)) {
                $respuesta['errors']  = $errores;
                $respuesta['message'] = "Corrige los errores del formulario.";
                break;
            }

            // Si no hay errores, intentamos guardar en la BD
            if ($producto->guardar()) {
                $respuesta['success'] = true;
                $respuesta['message'] = "Producto guardado correctamente.";
            } else {
                $respuesta['message'] = "No se pudo guardar el producto.";
            }
            break;

        // Editar un producto existente
        case 'editar':
            $errores = $producto->validar();
            if (!empty($errores)) {
                $respuesta['errors']  = $errores;
                $respuesta['message'] = "Corrige los errores del formulario.";
                break;
            }

            if ($producto->editar()) {
                $respuesta['success'] = true;
                $respuesta['message'] = "Producto editado correctamente.";
            } else {
                $respuesta['message'] = "No se pudo editar el producto.";
            }
            break;

        // Buscar productos por nombre (puede devolver varios)
        case 'buscar':
            // Nombre que se envía desde JS
            $nombre = $_POST['nombre'] ?? '';

            // Llamamos al método que busca por nombre
            $rows = $producto->buscarPorNombre($nombre);

            if (!empty($rows)) {
                $respuesta['success'] = true;
                $respuesta['data']    = $rows;
                $respuesta['message'] = "Se encontraron productos que coinciden.";
            } else {
                $respuesta['message'] = "No se encontraron productos con ese nombre.";
            }
            break;

        // Listar todos los productos (para llenar la tabla al inicio)
        case 'listar':
            $rows = $producto->listarTodos();
            $respuesta['success'] = true;
            $respuesta['data']    = $rows;
            break;

        // Eliminar un producto por su ID
        case 'eliminar':
            $id = $_POST['id'] ?? null;

            if ($id && $producto->eliminar($id)) {
                $respuesta['success'] = true;
                $respuesta['message'] = "Producto eliminado correctamente.";
            } else {
                $respuesta['message'] = "No se pudo eliminar el producto.";
            }
            break;

        // Si la acción no coincide con ninguna de las anteriores
        default:
            $respuesta['message'] = "Acción inválida.";
            break;
    }

    // Guardamos la acción usada, para referencia desde JS
    $respuesta['accion'] = $accion;

} catch (Exception $e) {
    // Si algo falla (error de PHP o BD) llegamos aquí
    $respuesta['message'] = "Error del servidor.";
}

// Devolvemos la respuesta en formato JSON
echo json_encode($respuesta);
