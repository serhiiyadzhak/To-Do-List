<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: register.php');
    exit();
}

require_once '../src/TaskRepository.php';

$taskRepo = new TaskRepository();

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];


$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $userId = $_SESSION['user_id'];

    if (!empty($title) && !empty($description)) {
        $taskRepo->createTask($title, $description, $userId);
        $_SESSION['success'] = "Завдання успішно додано!";
        header('Location: index.php');
        exit();
    } else {
        $error = "Будь ласка, заповніть усі поля.";
    }
}

$status = $_GET['status'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

$tasks = $taskRepo->getFilteredTasks($userId, $status, $sort);

if (!empty($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <!-- Підключення Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>To-Do List</h1>
            <div>
                <span class="me-3">Привіт, <?= htmlspecialchars($username) ?>!</span>
                <form action="logout.php" method="POST" class="d-inline">
                    <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                </form>
            </div>
        </div>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="GET" action="index.php" class="d-flex gap-3 mb-4">
            <select name="status" id="status" class="form-select">
                <option value="">Всі статуси</option>
                <option value="pending" <?= isset($_GET['status']) && $_GET['status'] === 'pending' ? 'selected' : '' ?>>Очікують</option>
                <option value="completed" <?= isset($_GET['status']) && $_GET['status'] === 'completed' ? 'selected' : '' ?>>Виконані</option>
            </select>
            <select name="sort" id="sort" class="form-select">
                <option value="newest" <?= isset($_GET['sort']) && $_GET['sort'] === 'newest' ? 'selected' : '' ?>>Нові -> Старі</option>
                <option value="oldest" <?= isset($_GET['sort']) && $_GET['sort'] === 'oldest' ? 'selected' : '' ?>>Старі -> Нові</option>
            </select>
            <button type="submit" class="btn btn-primary">Застосувати</button>
        </form>

        <ul class="list-group mb-4">
            <?php foreach ($tasks as $task): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?= htmlspecialchars($task->title) ?></strong>
                        <p class="mb-0"><?= htmlspecialchars($task->description) ?></p>
                        <small class="text-muted"><?= htmlspecialchars($task->status) ?></small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="edit_task.php?id=<?= htmlspecialchars($task->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                        <?php if ($task->status === 'pending'): ?>
                            <form action="update_status.php" method="POST">
                                <input type="hidden" name="id" value="<?= $task->id ?>">
                                <button type="submit" class="btn btn-success btn-sm">Done</button>
                            </form>
                        <?php elseif ($task->status === 'completed'): ?>
                            <form action="undo_task.php" method="POST">
                                <input type="hidden" name="id" value="<?= $task->id ?>">
                                <button type="submit" class="btn btn-secondary btn-sm">Undo</button>
                            </form>
                        <?php endif; ?>
                        <form action="delete_task.php" method="POST">
                            <input type="hidden" name="id" value="<?= $task->id ?>">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Ви дійсно хочете видалити це завдання?')">Delete</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <h2 class="mb-3">Додати нове завдання</h2>
        <form method="POST" action="index.php" class="bg-white p-4 rounded shadow-sm">
            <div class="mb-3">
                <label for="title" class="form-label">Заголовок</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Опис</label>
                <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Додати</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>