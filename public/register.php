<?php

require_once __DIR__ . '/../src/UserRepository.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        $userRepo = new UserRepository();
        $existingUser = $userRepo->getUserByUsername($username);

        if ($existingUser) {
            $error = "Користувач з таким іменем вже існує.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $userRepo->registerUser($username, $hashedPassword);
            $success = "Реєстрація успішна! Ви можете увійти.";
        }
    } else {
        $error = "Будь ласка, заповніть усі поля.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реєстрація</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="text-center mb-4">Реєстрація</h1>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php" class="bg-white p-4 rounded shadow-sm">
            <div class="mb-3">
                <label for="username" class="form-label">Ім'я користувача</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Зареєструватися</button>
        </form>

        <p class="mt-3">Вже маєте обліковий запис? <a href="login.php">Увійти</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>