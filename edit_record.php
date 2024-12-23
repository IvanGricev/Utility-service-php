<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $table = $_POST['table'];
    $id = $_POST['id'];
    $data = $_POST;
    unset($data['table'], $data['id']);

    // Определяем идентификатор для каждой таблицы
    $id_field = $table == 'users' ? 'user_id' : ($table == 'services' ? 'service_id' : ($table == 'payments' ? 'payment_id' : ($table == 'feedback' ? 'feedback_id' : ($table == 'receipts' ? 'receipt_id' : 'action_id'))));

    // Формирование SQL запроса для обновления записи
    $set = [];
    foreach ($data as $column => $value) {
        $set[] = "$column = :$column";
    }
    $set = implode(", ", $set);
    $query = "UPDATE $table SET $set WHERE $id_field = :id";
    $stmt = $pdo->prepare($query);
    $data['id'] = $id;
    $stmt->execute($data);

    header("Location: admin_panel.php");
    exit();
}
?>
