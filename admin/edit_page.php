<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard</title>
<style>
form {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Style for form labels */
label {
    display: block;
    margin-bottom: 8px;
}

/* Style for form input fields and textarea */
input[type="text"],
textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
}

/* Style for checkbox */
input[type="checkbox"] {
    margin-top: 10px;
}

/* Style for submit button */
input[type="submit"] {
    background-color: #007BFF;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

/* Hover effect for the submit button */
input[type="submit"]:hover {
    background-color: #0056b3;
}
</style>

<script language="javascript" type="text/javascript" src="js/tinymce/tinymce.min.js" ></script>
  <script type="text/javascript">
  tinymce.init({
    selector: '#content'
  });
  </script>
  </head>
  <?php


    session_start();
    include '../includes/db.php';

    // Check if the user is logged in and has admin privileges
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: login.php"); // Redirect to the login page if not logged in or not an admin
        exit();
    }

    // Initialize variables
    $page = [];
    $imageURL = '';

    // Check if an ID is provided in the URL
    if (isset($_GET['id'])) {
        $pageId = $_GET['id'];

        // Query the database to fetch the page details by ID
        try {
            $stmt = $pdo->prepare("SELECT id, slug, title, content, image_url, show_in_sidebar FROM pages WHERE id = :pageId");
            $stmt->bindParam(':pageId', $pageId, PDO::PARAM_INT);
            $stmt->execute();
            $page = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$page) {
                // Page not found, redirect to admin panel or display an error message
                header("Location: admin_panel.php");
                exit();
            }

            // Set the image URL for display
            $imageURL = $page['image_url'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    }

    // Handle form submission to update the page content
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        $slug = $_POST['slug'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $showInSidebar = isset($_POST['show_in_sidebar']) ? 1 : 0;

        // Handle image upload if a new image is selected
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageFileName = $_FILES['image']['name'];
            $imageTempPath = $_FILES['image']['tmp_name'];

            // Create a unique filename to avoid overwriting existing images
            $uniqueFileName = uniqid() . '_' . $imageFileName;
            $destination = $imageUploadDir . $uniqueFileName;

            if (move_uploaded_file($imageTempPath, $destination)) {
                $imageURL = '/DCMS/images/' . $uniqueFileName; // Store the image URL
            } else {
                echo "Failed to upload image.<br>";
            }
        }

        // Update the page content in the database
// Update the page content in the database
try {
    if (isset($pageId)) {
        // Update existing page
        $updateStmt = $pdo->prepare("UPDATE pages SET slug = :slug, title = :title, content = :content, image_url = :imageURL, show_in_sidebar = :showInSidebar WHERE id = :pageId");
        $updateStmt->execute(['slug' => $slug, 'title' => $title, 'content' => $content, 'showInSidebar' => $showInSidebar, 'imageURL' => $imageURL, 'pageId' => $pageId]);

        // Fetch the latest content from the database
        $stmt = $pdo->prepare("SELECT id, slug, title, content, image_url, show_in_sidebar FROM pages WHERE id = :pageId");
        $stmt->bindParam(':pageId', $pageId, PDO::PARAM_INT);
        $stmt->execute();
        $page = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        // Insert new page
        $insertStmt = $pdo->prepare("INSERT INTO pages (slug, title, content, image_url, show_in_sidebar ) VALUES (:slug, :title, :content, :imageURL, :showInSidebar )");
        $insertStmt->execute(['slug' => $slug, 'title' => $title, 'content' => $content, 'showInSidebar' => $showInSidebar, 'imageURL' => $imageURL]);

        // Fetch the latest content from the database
        $pageId = $pdo->lastInsertId(); // Get the ID of the newly inserted page
        $stmt = $pdo->prepare("SELECT id, slug, title, content, image_url, show_in_sidebar FROM pages WHERE id = :pageId");
        $stmt->bindParam(':pageId', $pageId, PDO::PARAM_INT);
        $stmt->execute();
        $page = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    echo "Page saved successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
    }
    ?>
    
<body>

    <h1>Edit Page</h1>
    <p>Edit the content of the page:</p>

    <form method="post" enctype="multipart/form-data">

        <label for="slug">Slug:</label>
        <input type="text" id="slug" name="slug" required value="<?= htmlspecialchars($page['slug'] ?? '') ?>">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required value="<?= htmlspecialchars($page['title'] ?? '') ?>">

        <textarea id="content" name="content"><?= htmlspecialchars($page['content'] ?? '') ?></textarea>

        <label for="show_in_sidebar">Show in Sidebar:</label>
        <input type="checkbox" id="show_in_sidebar" name="show_in_sidebar" value="1" <?= ($page['show_in_sidebar'] ?? 0) ? 'checked' : '' ?>>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*">

        <input type="submit" name="update" value="Update">
    </form>

    <a href="admin_panel.php">Back to Admin Panel</a> <!-- Add a link to return to the admin panel -->
    <a href="logout.php">Logout</a> <!-- Add a logout link -->
</body>
</html>
