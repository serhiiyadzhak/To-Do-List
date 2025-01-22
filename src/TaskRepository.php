<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Task.php';

class TaskRepository {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAllTasks() {
        $stmt = $this->db->query("SELECT * FROM tasks");
        $tasks = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tasks[] = new Task($row['id'], $row['title'], $row['description'], $row['status'], $row['created_at'], $row['updated_at']);
        }
        return $tasks;
    }

    public function createTask(string $title, string $description, int $userId): void {
        $stmt = $this->db->prepare("
            INSERT INTO tasks (title, description, user_id) 
            VALUES (:title, :description, :user_id)
        ");
        $stmt->execute(['title' => $title, 'description' => $description, 'user_id' => $userId]);
    }    

    public function getTaskById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Task($row['id'], $row['title'], $row['description'], $row['status'], $row['created_at'], $row['updated_at']);
        }
        return null;
    }

    public function getTasksByUserId(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }    
    
    public function updateTask($id, $title, $description) {
        $stmt = $this->db->prepare("UPDATE tasks SET title = :title, description = :description, updated_at = NOW() WHERE id = :id");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function markAsDone($id) {
        $stmt = $this->db->prepare("UPDATE tasks SET status = 'completed', updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function undoTask($id) {
        $stmt = $this->db->prepare("UPDATE tasks SET status = 'pending', updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public function deleteTask($id) {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }    
    public function getFilteredTasks($userId, $status, $sort) {
        $query = "SELECT * FROM tasks";
        $conditions = [];
        $params = [];
    
        $conditions[] = "user_id = :user_id";
        $params[':user_id'] = $userId;
    
        if (!empty($status)) {
            $conditions[] = "status = :status";
            $params[':status'] = $status;
        }
    
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
    
        if ($sort === 'newest') {
            $query .= " ORDER BY created_at DESC";
        } elseif ($sort === 'oldest') {
            $query .= " ORDER BY created_at ASC";
        }
    
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
    
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
   