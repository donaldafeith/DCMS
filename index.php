<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page Navigation</title>
    <style>
        /* CSS for horizontal navigation */
        ul.nav {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        ul.nav li {
            display: inline;
            margin-right: 10px; /* Adjust the spacing between navigation items as needed */
        }

        ul.nav li a {
            text-decoration: none;
            color: #333; /* Adjust the link color as needed */
            font-weight: bold;
        }

        ul.nav li a:hover {
            color: #007BFF; /* Adjust the link color on hover as needed */
        }
    </style>
</head>
<body>
    <?php
    session_start();
    require_once 'includes/db.php';

    // Get all pages from the database
    $stmt = $pdo->query("SELECT * FROM pages");
    $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <ul class="nav">
        <?php foreach ($pages as $page): ?>
            <li>
                <a href="index.php?page=<?php echo htmlspecialchars($page['slug']); ?>">
                    <?php echo htmlspecialchars($page['title']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php
    // Get the page slug from the URL, default to 'Home'
    $slug = isset($_GET['page']) ? $_GET['page'] : 'home';

    // Prepare and execute the query to fetch page content
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = :slug");
    $stmt->execute(['slug' => $slug]);

    $page = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($page) {
        // Display the page content
        echo "<h1>" . htmlspecialchars($page['title']) . "</h1>";
        echo $page['content']; // Assuming content contains safe HTML
    } else {
        // Page not found, display a 404 message or include a 404 page
        echo "<h1>Page not found</h1>";
    }
    ?>
</body>
</html>
