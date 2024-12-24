<?php include("header.php"); ?>

<main class="pt-3">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="mb-4">Добро пожаловать на сайт БелУслуги</h1>
                <p class="lead">Здесь вы можете управлять своими коммунальными услугами, совершать платежи и получать поддержку.</p>
            </div>
        </div>
        <div class="row py-4">
            <div class="col-md-4 mb-4">
                <div class="card h-100 py-2">
                    <div class="card-body text-center">
                        <i class="bi bi-credit-card-2-front display-4 text-primary"></i>
                        <h5 class="card-title mt-3">Оплата услуг</h5>
                        <p class="card-text">Удобная и быстрая оплата коммунальных услуг в режиме онлайн.</p>
                        <a href="services.php" class="btn btn-primary">Подробнее</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 py-2">
                    <div class="card-body text-center">
                        <i class="bi bi-file-earmark-text display-4 text-primary"></i>
                        <h5 class="card-title mt-3">Услуги</h5>
                        <p class="card-text">Получите полный список доступных коммунальных услуг и их описания.</p>
                        <a href="services.php" class="btn btn-primary">Подробнее</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 py-3">
                    <div class="card-body text-center">
                        <i class="bi bi-chat-left-text display-4 text-primary"></i>
                        <h5 class="card-title mt-3">Обратная связь</h5>
                        <p class="card-text">Получите помощь и поддержку от нашей команды.</p>
                        <a href="feedback.php" class="btn btn-primary">Подробнее</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h2>Наши услуги</h2>
                <?php
                $query = "SELECT * FROM services LIMIT 3";
                $stmt = $pdo->query($query);
                $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <div class="row">
                    <?php foreach ($services as $service): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 py-2">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($service['service_name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($service['description']); ?></p>
                                <p class="card-text"><?php echo htmlspecialchars($service['cost']); ?> BYN</p>
                                <a href="services.php" class="btn btn-primary">Подробнее</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center">
                    <a href="services.php" class="btn btn-secondary">Смотреть все услуги</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include("footer.php"); ?>
