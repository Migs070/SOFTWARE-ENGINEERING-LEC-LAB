<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: index.php"); exit; }

$authors = $pdo->query("SELECT author_id, CONCAT(first_name, ' ', last_name) AS name FROM authors")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title           = $_POST['title'];
    $isbn            = $_POST['isbn'];
    $price           = $_POST['price'];
    $published_year  = $_POST['published_year'];
    $author_id       = $_POST['author_id'];
    $last_updated_by = $_SESSION['user_id'];

    $stmt = $pdo->prepare("UPDATE books SET title = ?, isbn = ?, price = ?, published_year = ?, author_id = ?, last_updated_by = ? WHERE book_id = ?");
    $stmt->execute([$title, $isbn, $price, $published_year, $author_id, $last_updated_by, $id]);

    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5" style="max-width: 500px;">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark fw-bold">Edit Book Details</div>
            <div class="card-body p-4">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Author (Foreign Key)</label>
                        <select name="author_id" class="form-select" required>
                            <?php foreach ($authors as $author): ?>
                                <option value="<?= $author['author_id'] ?>" <?= $author['author_id'] == $book['author_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($author['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Book Title</label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($book['title']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ISBN</label>
                        <input type="text" name="isbn" class="form-control" value="<?= htmlspecialchars($book['isbn']) ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?= $book['price'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Published Year</label>
                            <input type="number" name="published_year" class="form-control" value="<?= $book['published_year'] ?>" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-warning">Update Book</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
