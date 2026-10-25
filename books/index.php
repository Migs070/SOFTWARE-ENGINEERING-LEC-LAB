<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM books WHERE book_id = ?");
    $stmt->execute([$_GET['delete_id']]);
    header("Location: index.php");
    exit;
}

$sql = "SELECT books.*, 
               CONCAT(authors.first_name, ' ', authors.last_name) AS author_name,
               u1.username AS added_by_user, 
               u2.username AS updated_by_user 
        FROM books 
        JOIN authors ON books.author_id = authors.author_id 
        LEFT JOIN users u1 ON books.added_by = u1.user_id 
        LEFT JOIN users u2 ON books.last_updated_by = u2.user_id 
        ORDER BY book_id DESC";
$books = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books Catalog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php">📚 Publishing System</a>
            <div class="navbar-nav ms-auto align-items-center">
                <a class="nav-link me-2" href="../authors/index.php">Authors</a>
                <a class="nav-link active me-3" href="index.php">Books</a>
                <span class="text-white me-3">User: <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
                <a class="btn btn-sm btn-outline-light" href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Books Catalog (Child Entity)</h2>
            <a href="create.php" class="btn btn-success">+ Add Book</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Price</th>
                            <th>Added By</th>
                            <th>Last Updated By</th>
                            <th>Last Updated At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book): ?>
                        <tr>
                            <td>#<?= $book['book_id'] ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($book['title']) ?></td>
                            <td><?= htmlspecialchars($book['author_name']) ?></td>
                            <td>$<?= number_format($book['price'], 2) ?></td>
                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($book['added_by_user']) ?></span></td>
                            <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($book['updated_by_user'] ?? 'N/A') ?></span></td>
                            <td><small class="text-muted"><?= $book['last_updated'] ?></small></td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $book['book_id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                <a href="index.php?delete_id=<?= $book['book_id'] ?>" onclick="return confirm('Delete book?')" class="btn btn-sm btn-outline-danger">Delete</a>
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
