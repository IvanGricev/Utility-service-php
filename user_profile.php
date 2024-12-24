<?php include("header.php"); ?>

<main class="main-content py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h2><?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                <p class="text-muted"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
            </div>
            <div class="col-md-4 text-end">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="settingsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsDropdown">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editModal">Изменить данные</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal">Удалить аккаунт</a></li>
                        <li><a class="dropdown-item" href="receipts.php">Просмотреть чеки</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <?php
        $query = "SELECT s.*, sv.service_name, sv.cost FROM subscriptions s JOIN services sv ON s.service_id = sv.service_id WHERE s.user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <!-- Список подписок -->
        <div class="row">
            <div class="col-12">
                <h2>Ваши подписки</h2>
                <form id="subscriptionsForm">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Выбрать</th>
                                <th>Услуга</th>
                                <th>Дата начала</th>
                                <th>Стоимость</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subscriptions as $subscription): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="subscription_ids[]" value="<?php echo $subscription['subscription_id']; ?>" data-cost="<?php echo $subscription['cost']; ?>" class="subscription-checkbox">
                                </td>
                                <td><?php echo htmlspecialchars($subscription['service_name']); ?></td>
                                <td><?php echo htmlspecialchars($subscription['start_date']); ?></td>
                                <td><?php echo htmlspecialchars($subscription['cost']); ?> BYN</td>
                                <td>
                                    <a href="unsubscribe.php?subscription_id=<?php echo $subscription['subscription_id']; ?>" class="btn btn-danger btn-sm">Отписаться</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="text-end">
                        <h4>Общая стоимость: <span id="totalCost">0</span> BYN</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal" id="payButton" disabled>Оплатить</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Модальное окно для изменения данных -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="edit_user.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Изменить данные</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="username" class="form-label">Имя пользователя</label>
                                <input type="text" class="form-control" id="username" name="username">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Новый пароль</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Введите текущий пароль для подтверждения</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Модальное окно для удаления аккаунта -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="delete_user.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Удалить аккаунт</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Введите пароль для подтверждения удаления аккаунта.</p>
                            <div class="mb-3">
                                <label for="passwordDelete" class="form-label">Пароль</label>
                                <input type="password" class="form-control" id="passwordDelete" name="password" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-danger">Удалить аккаунт</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Модальное окно для оплаты -->
        <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="pay.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">Оплата услуг</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="total_cost" id="totalCostInput">
                            <input type="hidden" name="subscription_ids" id="subscriptionIdsInput">
                            <div class="mb-3">
                                <label for="card_number" class="form-label">Номер карты</label>
                                <input type="text" class="form-control" id="card_number" name="card_number" required>
                                <small id="cardType" class="form-text text-muted"></small>
                            </div>
                            <div class="mb-3">
                                <label for="card_expiry" class="form-label">Срок действия (MM/YY)</label>
                                <input type="text" class="form-control" id="card_expiry" name="card_expiry" required>
                            </div>
                            <div class="mb-3">
                                <label for="card_cvc" class="form-label">CVC</label>
                                <input type="text" class="form-control" id="card_cvc" name="card_cvc" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-primary">Оплатить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subscriptionCheckboxes = document.querySelectorAll('.subscription-checkbox');
    const totalCostElement = document.getElementById('totalCost');
    const payButton = document.getElementById('payButton');
    const totalCostInput = document.getElementById('totalCostInput');
    const subscriptionIdsInput = document.getElementById('subscriptionIdsInput');
    const cardNumberInput = document.getElementById('card_number');
    const cardTypeElement = document.getElementById('cardType');

    subscriptionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            let totalCost = 0;
            let subscriptionIds = [];

            subscriptionCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    totalCost += parseFloat(checkbox.getAttribute('data-cost'));
                    subscriptionIds.push(checkbox.value);
                }
            });

            totalCostElement.textContent = totalCost.toFixed(2);
            totalCostInput.value = totalCost.toFixed(2);
            subscriptionIdsInput.value = subscriptionIds.join(',');
            payButton.disabled = subscriptionIds.length === 0;
        });
    });

    cardNumberInput.addEventListener('input', () => {
        const cardNumber = cardNumberInput.value;
        let cardType = '';

        if (/^4/.test(cardNumber)) {
            cardType = 'Visa';
        } else if (/^5[1-5]/.test(cardNumber)) {
            cardType = 'Mastercard';
        } else {
            cardType = 'Неподдерживаемый тип карты';
        }

        cardTypeElement.textContent = cardType;
    });
});
</script>


<?php include("footer.php"); ?>