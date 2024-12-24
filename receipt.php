<?php
session_start();
if (!isset($_SESSION['receipt'])) {
    header('Location: index.php');
    exit();
}

$receipt = $_SESSION['receipt'];
unset($_SESSION['receipt']);

require 'config.php';
?>

<?php include("header.php"); ?>

<main class="main-content py-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Чек</h2>
                <p>Спасибо за вашу оплату! Ниже приведены детали вашего платежа:</p>
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th>Имя пользователя</th>
                            <td><?php echo htmlspecialchars($_SESSION['username']); ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo htmlspecialchars($_SESSION['email']); ?></td>
                        </tr>
                        <tr>
                            <th>Дата платежа</th>
                            <td><?php echo htmlspecialchars($receipt['payment_date']); ?></td>
                        </tr>
                        <tr>
                            <th>Тип карты</th>
                            <td><?php echo htmlspecialchars($receipt['card_type']); ?></td>
                        </tr>
                        <tr>
                            <th>Общая стоимость</th>
                            <td><?php echo htmlspecialchars($receipt['total_cost']); ?> BYN</td>
                        </tr>
                        <tr>
                            <th>Идентификаторы подписок</th>
                            <td><?php echo htmlspecialchars(implode(', ', $receipt['subscription_ids'])); ?></td>
                        </tr>
                    </tbody>
                </table>
                <a href="index.php" class="btn btn-primary">Вернуться на главную</a>
            </div>
        </div>
    </div>
</main>

<?php include("footer.php"); ?>
