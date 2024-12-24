<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $date = date('Y-m-d');

    // Вставка отзыва в базу данных
    $query = "INSERT INTO feedback (user_id, message, date) VALUES (:user_id, :message, :date)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $user_id, 'message' => $message, 'date' => $date]);
}

// Получение всех отзывов из базы данных
$query = "SELECT f.*, u.username FROM feedback f JOIN users u ON f.user_id = u.user_id ORDER BY f.date DESC";
$stmt = $pdo->query($query);
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include("header.php"); ?>

<main class="main-content py-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Отзывы</h2>
                <!-- Форма добавления отзыва -->
                <form action="feedback.php" method="POST">
                    <div class="mb-3">
                        <label for="message" class="form-label">Ваш отзыв</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </form>

                <!-- Список отзывов -->
                <div class="mt-4">
                    <h3>Последние отзывы</h3>
                    <?php if (count($feedbacks) > 0): ?>
                    <ul class="list-group">
                        <?php foreach ($feedbacks as $feedback): ?>
                        <li class="list-group-item">
                            <h5><?php echo htmlspecialchars($feedback['username']); ?></h5>
                            <p><?php echo htmlspecialchars($feedback['message']); ?></p>
                            <small class="text-muted"><?php echo htmlspecialchars($feedback['date']); ?></small>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p>Отзывов пока нет.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include("footer.php"); ?>
