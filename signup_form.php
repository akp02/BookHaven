<?php
session_start();
$error_msg = '';
if (isset($_GET['error'])) {
    $codes = array(
        'missing'  => 'Please fill in every required field.',
        'short'    => 'Password must be at least 6 characters long.',
        'mismatch' => 'The two password entries did not match.',
        'taken'    => 'That username is already taken. Please choose another.',
        'failed'   => 'Database error while creating your account. Please try again.',
    );
    $code = $_GET['error'];
    $error_msg = isset($codes[$code]) ? $codes[$code] : 'Something went wrong. Please try again.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookHaven &mdash; Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <div class="login-card signup-card">
        <h1>Create your account</h1>
        <p class="subtitle">Join BookHaven</p>
        <?php if ($error_msg !== ''): ?>
            <div class="message error"><?php echo htmlspecialchars($error_msg); ?></div>
        <?php endif; ?>
        <form action="handle_signup.php" method="post" class="login-form">
            <div class="field">
                <label for="user_name">Username</label>
                <input type="text" id="user_name" name="user_name" maxlength="50" required autofocus>
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" maxlength="100" required>
            </div>
            <div class="field-row">
                <div class="field">
                    <label for="first_name">First name</label>
                    <input type="text" id="first_name" name="first_name" maxlength="50" required>
                </div>
                <div class="field">
                    <label for="last_name">Last name</label>
                    <input type="text" id="last_name" name="last_name" maxlength="50" required>
                </div>
            </div>
            <div class="field">
                <label for="password">Password (min 6 characters)</label>
                <input type="password" id="password" name="password" minlength="6" required>
            </div>
            <div class="field">
                <label for="confirm_password">Confirm password</label>
                <input type="password" id="confirm_password" name="confirm_password" minlength="6" required>
            </div>
            <button type="submit" class="btn-primary">Create account</button>
        </form>
        <p class="footer-link">Already have an account? <a href="login_form.php">Log in</a></p>
    </div>
</body>
</html>
