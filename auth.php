<?php
session_start();
require 'config.php';

$error = '';
$success = '';

// Регистрация
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Валидация
    $emailPattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/";

    if (!preg_match($emailPattern, $email)) {
        $error = 'Введите правильный email в формате "email@mail.domen".';
        header('Location: register.php?error=' . urlencode($error));
        exit();
    }

    if (!preg_match($passwordPattern, $password)) {
        $error = 'Пароль должен содержать буквы нижнего и верхнего регистра, цифры, спецсимволы и быть длиной минимум 6 символов.';
        header('Location: register.php?error=' . urlencode($error));
        exit();
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Проверка существующего пользователя
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->rowCount() > 0) {
        $error = 'Пользователь с таким email уже существует';
        header('Location: register.php?error=' . urlencode($error));
        exit();
    }

    // Вставка нового пользователя
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email, role) VALUES (:username, :password, :email, 'user')");
    $stmt->execute(['username' => $username, 'password' => $passwordHash, 'email' => $email]);

    // Автоматическая авторизация
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['role'] = 'user';
    header('Location: index.php');
    exit();
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
        header('Location: login.php?error=' . urlencode($error));
        exit();
    }
}

// Выход
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}
?>
