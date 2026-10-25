<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM authors WHERE author_id = ?");
    $stmt->execute([$_GET['delete_id']]);
    header("Location: index.php");
    exit;
}

$sql = "SELECT authors.*, 
               u1.username AS added_by_user, 
               u2.username AS updated_by_user 
        FROM authors 
        LEFT JOIN users u1 ON authors.added_by = u1.user_id 
        LEFT JOIN users u2 ON authors.last_updated_by = u2.user_id 
        ORDER BY author_id DESC";
$authors = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Authors List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php">📚 Publishing System</a>
            <div class="navbar-nav ms-auto align-items-center">
                <a class="nav-link active me-2" href="index.php">Authors</a>
                <a class="nav-link me-3" href="../books/index.php">Books</a>
                <span class="text-white me-3">User: <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
                <a class="btn btn-sm btn-outline-light" href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Authors Directory (Parent Entity)</h2>
            <a href="create.php" class="btn btn-success">+ Add Author</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Added By</th>
                            <th>Last Updated By</th>
                            <th>Last Updated At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($authors as $author): ?>
                        <tr>
                            <td>#<?= $author['author_id'] ?></td>
                            <td><?= htmlspecialchars($author['first_name'] . ' ' . $author['last_name']) ?></td>
                            <td><?= htmlspecialchars($author['email']) ?></td>
                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($author['added_by_user']) ?></span></td>
                            <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($author['updated_by_user'] ?? 'N/A') ?></span></td>
                            <td><small class="text-muted"><?= $author['last_updated'] ?></small></td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $author['author_id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                <a href="index.php?delete_id=<?= $author['author_id'] ?>" onclick="return confirm('Delete author and books?')" class="btn btn-sm btn-outline-danger">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
