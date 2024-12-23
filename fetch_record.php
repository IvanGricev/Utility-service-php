<?php
require 'config.php';

$table = $_GET['table'];
$id = $_GET['id'];

// Определяем идентификатор для каждой таблицы
$id_field = $table == 'users' ? 'user_id' : ($table == 'services' ? 'service_id' : ($table == 'payments' ? 'payment_id' : ($table == 'feedback' ? 'feedback_id' : ($table == 'receipts' ? 'receipt_id' : 'action_id'))));

$query = "SELECT * FROM $table WHERE $id_field = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($row);
?>
