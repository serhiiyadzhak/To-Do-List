<?php
require_once '../src/TaskRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['id'] ?? null;

    if ($taskId) {
        $taskRepo = new TaskRepository();
        $success = $taskRepo->markAsDone($taskId);

        if ($success) {
            header('Location: index.php?message=Завдання виконано');
            exit();
        } else {
            echo "Помилка: Не вдалося оновити статус.";
        }
    } else {
        echo "Помилка: Неправильний запит.";
    }
}
?>

