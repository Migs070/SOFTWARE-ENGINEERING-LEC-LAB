<?php
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Publishing System Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">📚 Publishing System</a>
            <div class="navbar-nav ms-auto align-items-center">
                <span class="nav-link text-white me-3">Logged in as: <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
                <a class="btn btn-sm btn-outline-light" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-5 text-center">
        <div class="p-5 bg-white rounded-3 shadow-sm border">
            <h1 class="display-5 fw-bold">Welcome, <?= htmlspecialchars($_SESSION['full_name']) ?>!</h1>
            <p class="lead text-muted mt-3">Select a module below to view and manage records with user audit tracking.</p>
            <hr class="my-4">
            <div class="d-flex justify-content-center gap-3">
                <a href="authors/index.php" class="btn btn-primary btn-lg shadow-sm">Manage Authors (Parent)</a>
                <a href="books/index.php" class="btn btn-outline-secondary btn-lg shadow-sm">Manage Books (Child)</a>
            </div>
        </div>
    </div>
</body>
</html>
