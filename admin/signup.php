<?php
session_start();
include 'db.php'; // Include your database connection code here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST['newUsername'];
    $newPassword = $_POST['newPassword'];
    $email = $_POST['email'];

    // Check if the username is already taken
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = :newUsername");
    $stmt->execute(['newUsername' => $newUsername]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        echo "Username already taken.";
    } else {
        // Insert the new user into the 'users' table
        $insertStmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (:newUsername, :newPassword, :email, 'admin')");
        $insertStmt->execute(['newUsername' => $newUsername, 'newPassword' => $newPassword, 'email' => $email]);

        echo "Signup successful. You can now <a href='login.html'>login</a>.";
    }
}
?>
