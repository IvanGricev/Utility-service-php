<?php include("header.php"); ?>

<main class="main-content py-3">
    <div class="container">
        <h2>Регистрация</h2>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        <form action="auth.php" method="POST" onsubmit="return validateForm()">
            <div class="mb-3">
                <label for="username" class="form-label">Имя пользователя</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="register">Зарегистрироваться</button>
        </form>
    </div>
</main>

<script>
function validateForm() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;

    if (!emailPattern.test(email)) {
        alert("Введите правильный email в формате 'email@mail.domen'.");
        return false;
    }
    if (!passwordPattern.test(password)) {
        alert("Пароль должен содержать буквы нижнего и верхнего регистра, цифры, спецсимволы и быть длиной минимум 6 символов.");
        return false;
    }
    return true;
}
</script>

<?php include("footer.php"); ?>
