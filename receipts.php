<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Получение всех чеков пользователя из базы данных
$query = "SELECT r.*, p.amount, p.date AS payment_date, s.service_name 
          FROM receipts r 
          JOIN payments p ON r.payment_id = p.payment_id 
          JOIN services s ON p.service_id = s.service_id 
          WHERE p.user_id = :user_id 
          ORDER BY r.date DESC";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$receipts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include("header.php"); ?>

<main class="main-content py-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Ваши чеки</h2>
                <?php if (count($receipts) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Дата чека</th>
                            <th>Дата платежа</th>
                            <th>Услуга</th>
                            <th>Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($receipts as $receipt): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($receipt['date']); ?></td>
                            <td><?php echo htmlspecialchars($receipt['payment_date']); ?></td>
                            <td><?php echo htmlspecialchars($receipt['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($receipt['amount']); ?> BYN</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>У вас нет чеков.</p>
                <?php endif; ?>
                <a href="user_profile.php" class="btn btn-primary">Вернуться в профиль</a>
            </div>
        </div>
    </div>
</main>

<?php include("footer.php"); ?>