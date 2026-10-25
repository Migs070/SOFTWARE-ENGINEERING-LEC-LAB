<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
    $added_by   = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO authors (first_name, last_name, email, added_by) VALUES (?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $email, $added_by]);

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Author</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5" style="max-width: 500px;">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white fw-bold">Add New Author</div>
            <div class="card-body p-4">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">Save Author</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
