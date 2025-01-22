<?php

require_once __DIR__ . '/../config/database.php';

class UserRepository {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function registerUser(string $username, string $password): bool {
        try {
            $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->execute(['username' => $username, 'password' => $password]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getUserByUsername(string $username): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
