<?php
session_start();
require 'config.php';

if (isset($_GET['table']) && isset($_GET['id'])) {
    $table = $_GET['table'];
    $id = $_GET['id'];

    // Определяем идентификатор для каждой таблицы
    $id_field = $table == 'users' ? 'user_id' : ($table == 'services' ? 'service_id' : ($table == 'payments' ? 'payment_id' : ($table == 'feedback' ? 'feedback_id' : ($table == 'receipts' ? 'receipt_id' : 'action_id'))));

    // Удаление связанных записей из таблицы receipts, если удаляется запись из payments
    if ($table == 'payments' || $table == 'services') {
        // Удаляем записи из receipts, которые связаны с payments, связанными с удаляемой услугой
        $deleteReceiptsQuery = "DELETE FROM receipts WHERE payment_id IN (SELECT payment_id FROM payments WHERE service_id = :id)";
        $deleteReceiptsStmt = $pdo->prepare($deleteReceiptsQuery);
        $deleteReceiptsStmt->execute(['id' => $id]);
    }

    // Удаление связанных записей из таблицы payments, если удаляется запись из services
    if ($table == 'services') {
        $deletePaymentsQuery = "DELETE FROM payments WHERE service_id = :id";
        $deletePaymentsStmt = $pdo->prepare($deletePaymentsQuery);
        $deletePaymentsStmt->execute(['id' => $id]);
    }

    // Удаление связанных записей из таблицы payments, если удаляется запись из users
    if ($table == 'users') {
        $deleteReceiptsQuery = "DELETE FROM receipts WHERE payment_id IN (SELECT payment_id FROM payments WHERE user_id = :id)";
        $deleteReceiptsStmt = $pdo->prepare($deleteReceiptsQuery);
        $deleteReceiptsStmt->execute(['id' => $id]);

        $deletePaymentsQuery = "DELETE FROM payments WHERE user_id = :id";
        $deletePaymentsStmt = $pdo->prepare($deletePaymentsQuery);
        $deletePaymentsStmt->execute(['id' => $id]);
    }

    // Удаление записи из базы данных
    $query = "DELETE FROM $table WHERE $id_field = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);

    header("Location: admin_panel.php");
    exit();
} else {
    die("Неверный запрос.");
}
?>
