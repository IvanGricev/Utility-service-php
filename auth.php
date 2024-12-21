<?php
require 'config.php';

$error = '';
$success = '';

// Регистрация
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];

    // Проверка существующего пользователя
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->rowCount() > 0) {
        $error = 'Пользователь с таким email уже существует';
    } else {
        // Вставка нового пользователя
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email, role) VALUES (:username, :password, :email, 'user')");
        $stmt->execute(['username' => $username, 'password' => $password, 'email' => $email]);

        // Автоматическая авторизация
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'user';
        header('Location: index.php');
        exit();
    }
}

// Авторизация
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Проверка пользователя и пароля
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
        exit();
    } else {
        $error = 'Неверные email или пароль';
    }
}

// Выход
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

// Переход к страницам с сообщениями об ошибках
if ($error) {
    $url = $_SERVER['HTTP_REFERER'];
    header('Location: ' . $url . '?error=' . urlencode($error));
    exit();
}
?>
