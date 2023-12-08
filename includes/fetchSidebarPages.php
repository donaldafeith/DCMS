<?php
// Assuming you have already included db.php and established a database connection

// Prepare and execute the query to fetch pages for the sidebar
$stmt = $conn->prepare("SELECT slug, title FROM pages WHERE show_in_sidebar = 1");
$stmt->execute();

$sidebarPages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>