<?php
require_once 'db_connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];  // Use email for login
    $password = $_POST['password'];

    // Query to check if the user exists with the entered email
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User exists, fetch the user's data
        $user = $result->fetch_assoc();

        // Verify the password with the hashed password in the database
        if (password_verify($password, $user['password'])) {
            // Start a session for the logged-in user
            session_start();
            $_SESSION['user_id'] = $user['id']; // Store user id in session
            $_SESSION['user_name'] = $user['name']; // Store user name in session

            // Redirect to a protected page, e.g., dashboard
            header("Location: Mainmenu.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email address.";
    }
}

// Close the connection
$conn->close();
?>
