<?php
// Include the database connection file
require_once 'db_connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
        exit;
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Redirect to login page after 2 seconds
        echo "Registration successful! Redirecting to login page...";
        header("Refresh: 2; url=login.html"); // Wait 2 seconds and redirect
        exit; // Prevent further script execution
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Close the connection
$conn->close();
?>
