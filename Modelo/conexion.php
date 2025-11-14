<?php
// Clase encargada de manejar la conexión a la base de datos
class DB {

    // Datos para conectarnos al servidor MySQL de XAMPP
    private $host = "localhost";          // Servidor
    private $dbname = "crud_fetch_mysql"; // Nombre de la base de datos
    private $user = "root";               // Usuario por defecto en XAMPP
    private $pass = "";                   // Contraseña (vacía en XAMPP)

    // Aquí guardaremos una única instancia de esta clase
    private static $instancia = null;

    // Aquí se guarda el objeto PDO que representa la conexión
    private $pdo;

    // Constructor privado: solo se llama desde getInstancia()
    private function __construct() {
        // Cadena de conexión (DSN) con la info de host y base de datos
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8";

        // Opciones para configurar el comportamiento de PDO
        $opciones = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // errores como excepciones
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // resultados como arreglo asociativo
        ];

        // Creamos la conexión usando PDO
        $this->pdo = new PDO($dsn, $this->user, $this->pass, $opciones);
    }

    // Devuelve la única instancia de DB (patrón Singleton)
    public static function getInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new DB();
        }
        return self::$instancia;
    }

    // Devuelve el objeto PDO para que otras clases hagan consultas
    public function getConexion() {
        return $this->pdo;
    }
}
