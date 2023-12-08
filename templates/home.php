<?php
$pageTitle = 'Home';
session_start();
require_once 'includes/db.php';

// Query the database to fetch content for the home page
$stmt = $conn->prepare("SELECT content FROM pages WHERE slug = 'home'");
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if content is found
if ($result) {
    $content = $result['content'];
} else {
    $content = "Page content not found.";
}

include 'layout.php';
?>