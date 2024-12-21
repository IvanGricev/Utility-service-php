<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $password = $_POST['password'];

    // Проверка пароля
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $_SESSION['email']]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        // Обновление данных пользователя
        if (!empty($username)) {
            $stmt = $pdo->prepare("UPDATE users SET username = :username WHERE user_id = :user_id");
            $stmt->execute(['username' => $username, 'user_id' => $user['user_id']]);
            $_SESSION['username'] = $username;
        }
        if (!empty($email)) {
            $stmt = $pdo->prepare("UPDATE users SET email = :email WHERE user_id = :user_id");
            $stmt->execute(['email' => $email, 'user_id' => $user['user_id']]);
            $_SESSION['email'] = $email;
        }
        if (!empty($new_password)) {
            $new_passwordHash = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE users SET password_hash = :password WHERE user_id = :user_id");
            $stmt->execute(['password' => $new_passwordHash, 'user_id' => $user['user_id']]);
        }
        header('Location: user_profile.php');
        exit();
    } else {
        header('Location: user_profile.php?error=Неверный пароль');
        exit();
    }
}
?>
