<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Menu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
     <!-- Container for the header -->
    <div class="main-menu-box">
        <!-- Image background with buttons overlayed -->
        <div class="menu-image">
            <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
            <button onclick="goToProfile()">Profile</button>
            <button onclick="goToLeaderboard()">Leaderboard</button>
            <button onclick="goToHowToPlay()">How to Play</button>
            <button onclick="goToSettings()">Settings</button>
            <button onclick="startGame()">Play</button>
            <button onclick="logout()">Logout</button> <!-- Logout Button -->
        </div>
    </div>

    <script>
        function goToProfile() {
            window.location.href = "profile.php";  // Change to PHP if needed
        }
        function goToLeaderboard() {
            window.location.href = "leaderboard.html";  // Change to PHP if needed
        }
        function goToHowToPlay() {
            window.location.href = "howtoplay.html";  // Change to PHP if needed
        }
        function goToSettings() {
            window.location.href = "settings.html";  // Change to PHP if needed
        }
        function startGame() {
            window.location.href = "play.html";  // Change to PHP if needed
        }
        function logout() {
            window.location.href = "logout.php";  // Redirect to logout.php to handle session destruction
        }
    </script>
</body>
</html>
