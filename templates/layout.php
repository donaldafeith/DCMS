<!DOCTYPE html>
<html>
<head>
    <title>Your CMS - <?= htmlspecialchars($pageTitle) ?></title>
    <!-- Add CSS and other head elements here -->
</head>
<body>
    <header>
        <h1>Your CMS</h1>
        <!-- Add navigation bar, logo, etc. -->
    </header>

    <div id="container">
        <aside id="sidebar">
            <!-- Include sidebar code or require a separate file -->
            <?php include 'sidebar.php'; ?>
        </aside>

        <main id="content">
            <!-- Main content will be loaded here -->
            <?= $content ?>
        </main>
    </div>

    <footer>
        <p>&copy; <?= date('Y') ?> Your CMS</p>
        <!-- Footer content -->
    </footer>
</body>
</html>