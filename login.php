<?php
require_once 'config/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['user_id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5" style="max-width: 420px;">
        <div class="card shadow-sm border-0 mt-5">
            <div class="card-header bg-dark text-white text-center fw-bold">System Login</div>
            <div class="card-body p-4">
                <?php if (isset($_GET['registered'])): ?>
                    <div class="alert alert-success small">Registration successful! Please login.</div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger small"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 fw-bold">Login</button>
                </form>
                <div class="text-center mt-3">
                    <small>Don't have an account? <a href="register.php">Register here</a></small>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
