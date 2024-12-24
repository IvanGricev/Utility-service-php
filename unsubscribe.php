<?php
session_start();
require 'config.php';

if (isset($_GET['subscription_id'])) {
    $subscription_id = $_GET['subscription_id'];
    $user_id = $_SESSION['user_id'];

    // Удаление подписки из базы данных
    $query = "DELETE FROM subscriptions WHERE subscription_id = :subscription_id AND user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['subscription_id' => $subscription_id, 'user_id' => $user_id]);

    header("Location: user_profile.php");
    exit();
} else {
    die("Неверный запрос.");
}
?>
