<?php
session_start();
require_once 'config.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = "Please enter username and password.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $user = $res->fetch_assoc();
            // password stored with SHA2
            if ($user['password'] === hash('sha256', $password)) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CampusFlow – Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">

<div class="auth-container">
    <div class="auth-card">
        <h1 class="app-title">CampusFlow</h1>
        <p class="app-subtitle">Smart Student Records</p>

        <form method="post" class="auth-form">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter username">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password">
            </div>

            <?php if ($error): ?>
                <p class="error-text"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <button type="submit" class="btn-primary full-width">Login</button>
        </form>
        <p class="hint">Demo: username <b>admin</b>, password <b>admin123</b></p>
    </div>
</div>

</body>
</html>
