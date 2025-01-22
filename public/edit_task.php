<?php
require_once '../src/TaskRepository.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$taskRepo = new TaskRepository();
$task = $taskRepo->getTaskById($_GET['id']); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $taskRepo->updateTask($_GET['id'], $title, $description); 
    header('Location: index.php'); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати завдання</title>
</head>
<body>
    <h1>Редагувати завдання</h1>
    <form action="edit_task.php?id=<?= htmlspecialchars($_GET['id']) ?>" method="POST">
        <label for="title">Заголовок:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($task->title) ?>" required><br><br>
        
        <label for="description">Опис:</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($task->description) ?></textarea><br><br>
        
        <button type="submit">Оновити завдання</button>
    </form>
</body>
</html>
