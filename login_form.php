<?php
session_start();
$message = '';
$message_type = '';
if (isset($_GET['error'])) {
    $message = 'Invalid username or password. Please try again.';
    $message_type = 'error';
} elseif (isset($_GET['signup']) && $_GET['signup'] === 'ok') {
    $message = 'Account created. Please log in with your new credentials.';
    $message_type = 'success';
} elseif (isset($_GET['logout']) && $_GET['logout'] === 'ok') {
    $message = 'You have been logged out.';
    $message_type = 'success';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookHaven &mdash; Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <div class="login-card">
        <h1>BookHaven</h1>
        <p class="subtitle">Online Bookstore</p>
        <?php if ($message !== ''): ?>
            <div class="message <?php echo htmlspecialchars($message_type); ?>"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form action="handle_form.php" method="post" class="login-form">
            <div class="field">
                <label for="user_name">Username</label>
                <input type="text" id="user_name" name="user_name" maxlength="50" required autofocus>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Log in</button>
        </form>
        <p class="footer-link">New customer? <a href="signup_form.php">Create an account</a></p>
    </div>
</body>
</html>
