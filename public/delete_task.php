<?php
require_once '../src/TaskRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        $taskRepo = new TaskRepository();
        $success = $taskRepo->deleteTask($id);

        if ($success) {
            $_SESSION['success'] = "Завдання успішно видалено.";
        } else {
            $_SESSION['error'] = "Не вдалося видалити завдання.";
        }
    }
    header('Location: index.php');
    exit;
}
?>
