<?php

class Database {
    private $host;
    private $port;
    private $db;
    private $user;
    private $password;
    public $conn;

    public function __construct() {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->port = getenv('DB_PORT') ?: '5432';
        $this->db = getenv('DB_NAME') ?: 'todo_list';
        $this->user = getenv('DB_USER') ?: 'postgres';
        $this->password = getenv('DB_PASSWORD') ?: 'password';
    }

    public function connect() {
        $this->conn = null;
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db}";
            $this->conn = new PDO($dsn, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Помилка підключення до бази даних: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>
