<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$service_id = $_GET['service_id'];
$start_date = date('Y-m-d');

// Вставка подписки в базу данных
$query = "INSERT INTO subscriptions (user_id, service_id, start_date) VALUES (:user_id, :service_id, :start_date)";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id, 'service_id' => $service_id, 'start_date' => $start_date]);

header('Location: index.php');
exit();
?>
