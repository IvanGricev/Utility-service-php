<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $subscription_ids = explode(',', $_POST['subscription_ids']);
    $total_cost = $_POST['total_cost'];
    $card_number = $_POST['card_number'];
    $card_expiry = $_POST['card_expiry'];
    $card_cvc = $_POST['card_cvc'];
    $payment_date = date('Y-m-d');

    // Валидация карты
    if (!preg_match('/^\d{16}$/', $card_number)) {
        die('Неверный номер карты');
    }
    if (!preg_match('/^\d{2}\/\d{2}$/', $card_expiry)) {
        die('Неверный срок действия карты');
    }
    if (!preg_match('/^\d{3}$/', $card_cvc)) {
        die('Неверный CVC код');
    }

    // Определение типа карты
    $card_type = '';
    if (preg_match('/^4/', $card_number)) {
        $card_type = 'Visa';
    } elseif (preg_match('/^5[1-5]/', $card_number)) {
        $card_type = 'Mastercard';
    } else {
        die('Неподдерживаемый тип карты');
    }

    // Вставка платежа и создание чека для каждой подписки
    foreach ($subscription_ids as $subscription_id) {
        // Получение стоимости услуги
        $query = "SELECT sv.cost FROM subscriptions s JOIN services sv ON s.service_id = sv.service_id WHERE s.subscription_id = :subscription_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['subscription_id' => $subscription_id]);
        $subscription = $stmt->fetch(PDO::FETCH_ASSOC);
        $service_cost = $subscription['cost'];

        // Вставка платежа в базу данных
        $query = "INSERT INTO payments (user_id, service_id, amount, date) VALUES (:user_id, :service_id, :amount, :payment_date)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['user_id' => $user_id, 'service_id' => $subscription_id, 'amount' => $service_cost, 'payment_date' => $payment_date]);
        $payment_id = $pdo->lastInsertId();

        // Вставка чека в базу данных
        $receipt_query = "INSERT INTO receipts (payment_id, date) VALUES (:payment_id, :date)";
        $receipt_stmt = $pdo->prepare($receipt_query);
        $receipt_stmt->execute(['payment_id' => $payment_id, 'date' => $payment_date]);
    }

    // Вывод чека
    $_SESSION['receipt'] = [
        'user_id' => $user_id,
        'total_cost' => $total_cost,
        'payment_date' => $payment_date,
        'card_type' => $card_type,
        'subscription_ids' => $subscription_ids
    ];

    header('Location: receipt.php');
    exit();
} else {
    die("Неверный запрос.");
}
?>
