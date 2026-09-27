<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header('Location: login_form.php');
    exit;
}
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 'yes') {
    header('Location: secret_page.php');
    exit;
}
require_once 'mysqli_connect.php';

$count_q = mysqli_query($dbc, 'SELECT COUNT(*) AS total FROM users');
$count_row = mysqli_fetch_assoc($count_q);
$total = (int)$count_row['total'];

$users = array();
$user_q = mysqli_query($dbc, 'SELECT user_name, email, first_name, last_name, is_admin FROM users ORDER BY user_name');
while ($row = mysqli_fetch_assoc($user_q)) {
    $users[] = $row;
}
mysqli_close($dbc);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookHaven &mdash; Registered Users</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topbar admin">
        <h1>BookHaven &mdash; Registered Users</h1>
        <nav>
            <a href="secret_page_admin.php">&larr; Back to admin panel</a>
            <a href="logout.php" class="btn-logout">Log out</a>
        </nav>
    </header>
    <main class="results">
        <p class="count">In total, the number of retrieved records is: <strong><?php echo $total; ?></strong></p>
        <table class="result-table">
            <thead>
                <tr><th>Username</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Role</th></tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($u['user_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($u['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($u['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo $u['is_admin'] === 'yes' ? 'Administrator' : 'Customer'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
