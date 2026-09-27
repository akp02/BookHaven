<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header('Location: login_form.php');
    exit;
}
$error_msg = '';
$success_msg = '';
if (isset($_GET['error'])) {
    $codes = array(
        'missing'  => 'Please fill in every field.',
        'current'  => 'Your current password is incorrect.',
        'short'    => 'New password must be at least 6 characters long.',
        'mismatch' => 'The new passwords do not match.',
        'failed'   => 'Database error while updating your password.',
    );
    $code = $_GET['error'];
    $error_msg = isset($codes[$code]) ? $codes[$code] : 'Unable to update password.';
}
if (isset($_GET['ok'])) {
    $success_msg = 'Your password has been updated successfully.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookHaven &mdash; Change Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar">
        <h1>BookHaven</h1>
        <nav>
            <a href="secret_page.php">&larr; Back to storefront</a>
            <a href="logout.php" class="btn-logout">Log out</a>
        </nav>
    </header>
    <main class="storefront">
        <section class="search-card">
            <h2>Change Password</h2>
            <?php if ($error_msg !== ''): ?>
                <div class="message error"><?php echo htmlspecialchars($error_msg); ?></div>
            <?php endif; ?>
            <?php if ($success_msg !== ''): ?>
                <div class="message success"><?php echo htmlspecialchars($success_msg); ?></div>
            <?php endif; ?>
            <form action="handle_update_password.php" method="post">
                <div class="field">
                    <label for="current_password">Current password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="field">
                    <label for="new_password">New password (min 6 characters)</label>
                    <input type="password" id="new_password" name="new_password" minlength="6" required>
                </div>
                <div class="field">
                    <label for="confirm_new_password">Confirm new password</label>
                    <input type="password" id="confirm_new_password" name="confirm_new_password" minlength="6" required>
                </div>
                <button type="submit" class="btn-primary">Update password</button>
            </form>
        </section>
    </main>
</body>
</html>
