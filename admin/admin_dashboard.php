<?php
session_start();
include '../includes/db.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to the login page if not logged in or not an admin
    exit();
}

// Query the database to fetch a list of pages
$pages = [];
try {
    $stmt = $pdo->query("SELECT id, slug, title FROM pages");
    $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Rest of your admin panel content goes here
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <p>Welcome to the Admin Panel. Here, you can edit pages or add new pages:</p>

    <ul>
        <?php foreach ($pages as $page) : ?>
            <li><a href="edit_page.php?id=<?= $page['id'] ?>"><?= $page['title'] ?></a></li>
        <?php endforeach; ?>
    </ul>

    <a href="add_page.php">Add New Page</a> <!-- Link to add_page.php -->
    <a href="logout.php">Logout</a> <!-- Add a logout link -->
</body>
</html>
