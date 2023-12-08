<?php
session_start();
include '../includes/db.php';

// Specify the absolute path to the "images" folder
$imageUploadDir = $_SERVER['DOCUMENT_ROOT'] . '/DCMS/images/';

// Initialize a variable to hold the image URL
$imageURL = '';

// Handle image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $imageFileName = $_FILES['image']['name'];
    $imageTempPath = $_FILES['image']['tmp_name'];

    // Create a unique filename to avoid overwriting existing images
    $uniqueFileName = uniqid() . '_' . $imageFileName;
    $destination = $imageUploadDir . $uniqueFileName;

    if (move_uploaded_file($imageTempPath, $destination)) {
        $imageURL = '/DCMS/images/' . $uniqueFileName; // Store the image URL
        echo "Image uploaded successfully.";
    } else {
        echo "Failed to upload image.";
    }
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the uploaded file
    // You can use $imageURL to display the uploaded image
    if (!empty($imageURL)) {
        echo "Uploaded Image URL: " . $imageURL;
        // You can also save $imageURL in your database or perform other actions here.
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Your CMS</title>
    <!-- Add your CSS styles and JavaScript here -->
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <label for="slug">Slug:</label>
        <input type="text" id="slug" name="slug" required>

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="content">Content:</label>
        <textarea id="content" name="content" required></textarea>

        <label for="show_in_sidebar">Show in Sidebar:</label>
        <input type="checkbox" id="show_in_sidebar" name="show_in_sidebar" value="1">

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*">

        <input type="submit" value="Save Page">
    </form>
</body>
</html>
