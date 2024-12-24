<?php include("header.php"); ?>

<main class="pt-3">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="mb-4">Доступные услуги</h1>
            </div>
        </div>
        <div class="row">
            <?php
            $query = "SELECT * FROM services";
            $stmt = $pdo->query($query);
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="col-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Название услуги</th>
                            <th>Описание</th>
                            <th>Стоимость</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $service): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($service['description']); ?></td>
                            <td><?php echo htmlspecialchars($service['cost']); ?> BYN</td>
                            <td>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="subscribe.php?service_id=<?php echo $service['service_id']; ?>" class="btn btn-primary">Подписаться</a>
                                <?php else: ?>
                                <a href="login.php" class="btn btn-secondary">Войти для подписки</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include("footer.php"); ?>
