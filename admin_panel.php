<?php include("header.php"); ?>
<?php require 'config.php'; ?>

<main class="py-3">
    <div class="container">
        <h2>Админ Панель</h2>
        <div class="accordion" id="accordionExample">
            <!-- Пользователи -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingUsers">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsers" aria-expanded="true" aria-controls="collapseUsers">
                        Пользователи
                    </button>
                </h2>
                <div id="collapseUsers" class="accordion-collapse collapse show" aria-labelledby="headingUsers" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <?php
                        $query = "SELECT user_id, username, email, role, createdAt, updatedAt FROM users";
                        $stmt = $pdo->query($query);
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <?php foreach ($rows[0] as $column => $value): ?>
                                        <th><?php echo htmlspecialchars($column); ?></th>
                                    <?php endforeach; ?>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $value): ?>
                                            <td><?php echo htmlspecialchars($value); ?></td>
                                        <?php endforeach; ?>
                                        <td>
                                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModalUsers" data-id="<?php echo $row['user_id']; ?>">Редактировать</a>
                                            <a href="delete_record.php?table=users&id=<?php echo $row['user_id']; ?>" class="btn btn-danger btn-sm">Удалить</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Другие таблицы -->
            <?php
            $tables = ['services', 'payments', 'feedback', 'receipts', 'actionhistory'];
            foreach ($tables as $table):
                $query = "SELECT * FROM $table";
                $stmt = $pdo->query($query);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // Определяем идентификатор для каждой таблицы
                $id_field = $table == 'services' ? 'service_id' : ($table == 'payments' ? 'payment_id' : ($table == 'feedback' ? 'feedback_id' : ($table == 'receipts' ? 'receipt_id' : 'action_id')));
            ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading<?php echo ucfirst($table); ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo ucfirst($table); ?>" aria-expanded="false" aria-controls="collapse<?php echo ucfirst($table); ?>">
                        <?php echo ucfirst($table); ?>
                    </button>
                </h2>
                <div id="collapse<?php echo ucfirst($table); ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo ucfirst($table); ?>" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <?php foreach ($rows[0] as $column => $value): ?>
                                        <th><?php echo htmlspecialchars($column); ?></th>
                                    <?php endforeach; ?>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $value): ?>
                                            <td><?php echo htmlspecialchars($value); ?></td>
                                        <?php endforeach; ?>
                                        <td>
                                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo ucfirst($table); ?>" data-id="<?php echo $row[$id_field]; ?>">Редактировать</a>
                                            <a href="delete_record.php?table=<?php echo $table; ?>&id=<?php echo $row[$id_field]; ?>" class="btn btn-danger btn-sm">Удалить</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Кнопка для добавления новой услуги -->
        <button class="btn btn-success mt-4" data-bs-toggle="modal" data-bs-target="#addServiceModal">Добавить услугу</button>
    </div>

    <!-- Модальные окна для редактирования записей -->
    <?php foreach (['users', 'services', 'payments', 'feedback', 'receipts', 'actionhistory'] as $table): ?>
    <div class="modal fade" id="editModal<?php echo ucfirst($table); ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo ucfirst($table); ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="edit_record.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel<?php echo ucfirst($table); ?>">Редактировать запись</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="table" value="<?php echo $table; ?>">
                        <input type="hidden" name="id" id="edit-id-<?php echo $table; ?>">
                        <!-- Поля формы будут динамически добавлены здесь с помощью JavaScript -->
                        <div id="edit-fields-<?php echo $table; ?>"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Модальное окно для добавления услуги -->
    <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="add_service.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addServiceModalLabel">Добавить услугу</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="service_name" class="form-label">Название услуги</label>
                            <input type="text" class="form-control" id="service_name" name="service_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Описание</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="cost" class="form-label">Стоимость</label>
                            <input type="number" class="form-control" id="cost" name="cost" step="0.01" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-success">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modals = <?php echo json_encode(['users', 'services', 'payments', 'feedback', 'receipts', 'actionhistory']); ?>;
        
        modals.forEach(table => {
            const editModal = document.getElementById(`editModal${table.charAt(0).toUpperCase() + table.slice(1)}`);
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                
                // Получение данных из строки таблицы и добавление их в форму
                fetch(`fetch_record.php?table=${table}&id=${id}`)
                    .then(response => response.json())
                    .then(data => {
                        const form = editModal.querySelector('form');
                        form.querySelector(`#edit-id-${table}`).value = id;
                        const fieldsDiv = form.querySelector(`#edit-fields-${table}`);
                        fieldsDiv.innerHTML = ''; // Очистка предыдущих полей
                        for (const [column, value] of Object.entries(data)) {
                            if (column !== 'password_hash') { // Исключение столбца пароля
                                fieldsDiv.innerHTML += `
                                    <div class="mb-3">
                                        <label for="${column}" class="form-label">${column}</label>
                                        <input type="text" class="form-control" id="${column}" name="${column}" value="${value}">
                                    </div>
                                `;
                            }
                        }
                    }).catch(error => {
                        const fieldsDiv = form.querySelector(`#edit-fields-${table}`);
                        fieldsDiv.innerHTML = `<p class="text-danger">Ошибка загрузки данных для редактирования</p>`;
                    });
                });
            });
        });
    </script>

</main>

<?php include("footer.php"); ?>
