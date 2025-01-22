<?php
require_once '../src/TaskRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['id'] ?? null;

    if ($taskId) {
        $taskRepo = new TaskRepository();
        $success = $taskRepo->undoTask($taskId);

        if ($success) {
            header('Location: index.php?message=Завдання відновлено');
            exit();
        } else {
            echo "Помилка: Не вдалося скасувати статус.";
        }
    } else {
        echo "Помилка: Неправильний запит.";
    }
}
?>
