<?php
// Clase Producto: aquí ponemos toda la lógica relacionada con la tabla "productos"

require_once 'conexion.php';

class Producto {

    // Propiedades que representan las columnas de la tabla
    public $id;
    public $codigo;
    public $producto;
    public $precio;
    public $cantidad;

    // Aquí guardamos la conexión a la base de datos
    private $db;

    // En el constructor obtenemos la conexión desde la clase DB
    public function __construct() {
        $this->db = DB::getInstancia()->getConexion();
    }

    // Valida los campos del formulario antes de guardar o editar
    public function validar() {
        $errores = [];

        if (empty($this->codigo)) {
            $errores['codigo'] = "El código es obligatorio.";
        }

        if (empty($this->producto)) {
            $errores['producto'] = "El nombre del producto es obligatorio.";
        }

        if ($this->precio === "" || !is_numeric($this->precio)) {
            $errores['precio'] = "El precio no es válido.";
        }

        if ($this->cantidad === "" || !ctype_digit((string)$this->cantidad)) {
            $errores['cantidad'] = "La cantidad debe ser un número entero.";
        }

        return $errores; // si no hay errores, el arreglo viene vacío
    }

    // Inserta un nuevo producto en la tabla
    public function guardar() {
        $sql = "INSERT INTO productos (codigo, producto, precio, cantidad)
                VALUES (:codigo, :producto, :precio, :cantidad)";

        // Preparamos la consulta y sustituimos los valores
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':codigo'   => $this->codigo,
            ':producto' => $this->producto,
            ':precio'   => $this->precio,
            ':cantidad' => $this->cantidad
        ]);
    }

    // Actualiza un producto existente (usa el ID)
    public function editar() {
        $sql = "UPDATE productos
                SET codigo = :codigo,
                    producto = :producto,
                    precio = :precio,
                    cantidad = :cantidad
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':codigo'   => $this->codigo,
            ':producto' => $this->producto,
            ':precio'   => $this->precio,
            ':cantidad' => $this->cantidad,
            ':id'       => $this->id
        ]);
    }

    // Lista todos los productos de la tabla
    public function listarTodos() {
        $sql = "SELECT * FROM productos ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Busca productos por NOMBRE usando LIKE (puede devolver varios)
    public function buscarPorNombre($nombre) {
        // Armamos el patrón %nombre% para la búsqueda parcial
        $nombre = "%$nombre%";

        $sql = "SELECT * FROM productos
                WHERE producto LIKE :nombre
                ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':nombre' => $nombre]);

        return $stmt->fetchAll();
    }

    // Elimina un producto según su ID
    public function eliminar($id) {
        $sql = "DELETE FROM productos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
