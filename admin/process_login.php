<?php
session_start();
include '../includes/db.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username and password match a record in the 'users' table
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $stmt->execute(['username' => $username, 'password' => $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // User found, store user information in a session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect to the admin dashboard or any other page
        header("Location: admin_dashboard.php");
        exit();
    } else {
        // Invalid credentials, redirect back to the login page with an error message
        header("Location: login.php?error=1");
        exit();
    }
} else {
    // Redirect back to the login page if accessed without POST data
    header("Location: login.php");
    exit();
}
?>
