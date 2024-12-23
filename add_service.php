<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $cost = $_POST['cost'];

    // Вставка новой услуги в базу данных
    $query = "INSERT INTO services (service_name, description, cost) VALUES (:service_name, :description, :cost)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['service_name' => $service_name, 'description' => $description, 'cost' => $cost]);

    header("Location: admin_panel.php");
    exit();
}
?>
