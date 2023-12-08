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
</head>
<script language="javascript" type="text/javascript" src="js/tinymce/tinymce.min.js" ></script>
  <script type="text/javascript">
  tinymce.init({
    selector: '#content'
  });
  </script>
<body>

<h1>Admin Area</h1>

<h2>Create Pages</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="slug">Slug:</label>
        <input type="text" id="slug" name="slug" required>

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>

        <textarea id="content" name="content"></textarea>

        <label for="show_in_sidebar">Show in Sidebar:</label>
        <input type="checkbox" id="show_in_sidebar" name="show_in_sidebar" value="1">

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*">

        <input type="submit" value="Save Page">
    </form>
</body>
</html>
<?php
    session_start();
    include '../includes/db.php';

    // Check if the user is logged in and has admin privileges
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: login.php"); // Redirect to the login page if not logged in or not an admin
        exit();
    }

// Specify the absolute path to the "images" folder
$imageUploadDir = $_SERVER['DOCUMENT_ROOT'] . '/DCMS/images/';

// Initialize a variable to hold the image URL
$imageURL = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFileName = $_FILES['image']['name'];
        $imageTempPath = $_FILES['image']['tmp_name'];

        // Create a unique filename to avoid overwriting existing images
        $uniqueFileName = uniqid() . '_' . $imageFileName;
        $destination = $imageUploadDir . $uniqueFileName;

        if (move_uploaded_file($imageTempPath, $destination)) {
            $imageURL = '/DCMS/images/' . $uniqueFileName; // Store the image URL
            echo "Image uploaded successfully.<br>";
        } else {
            echo "Failed to upload image.<br>";
        }
    }

    $slug = $_POST['slug'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $showInSidebar = isset($_POST['show_in_sidebar']) ? 1 : 0;

    // Establish a database connection (assuming you have a 'conn' variable in your 'db.php' file)
    include '../includes/db.php'; // Adjust the path to your database connection script

    // Insert or Update logic
    $stmt = $pdo->prepare("SELECT id FROM pages WHERE slug = :slug");
    $stmt->bindParam(':slug', $slug); // Bind the parameter
    $stmt->execute(); // Execute the statement
    $existingPage = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($existingPage) {
        // Update existing page
        $updateStmt = $pdo->prepare("UPDATE pages SET title = :title, content = :content, image_url = :imageURL, show_in_sidebar = :showInSidebar WHERE slug = :slug");
        $updateStmt->execute(['title' => $title, 'content' => $content, 'showInSidebar' => $showInSidebar, 'imageURL' => $imageURL, 'slug' => $slug]);
    } else {
        // Insert new page
        $insertStmt = $pdo->prepare("INSERT INTO pages (slug, title, content, image_url, show_in_sidebar ) VALUES (:slug, :title, :content, :imageURL, :showInSidebar )");
        $insertStmt->execute(['slug' => $slug, 'title' => $title, 'content' => $content, 'showInSidebar' => $showInSidebar, 'imageURL' => $imageURL]);
    }

    echo "Page saved successfully.";
}
?>