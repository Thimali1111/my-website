<?php
// Include database connection
require_once 'db_connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.html");
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT name FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

// Fetch game stats
$sql_stats = "SELECT SUM(score) AS total_score, SUM(total_time_played) AS total_time_played FROM game_stats WHERE user_id = ?";
$stmt_stats = $conn->prepare($sql_stats);
$stmt_stats->bind_param("i", $user_id);
$stmt_stats->execute();
$result_stats = $stmt_stats->get_result();
$stats = $result_stats->fetch_assoc();

// Format total time played
$seconds = $stats['total_time_played'];
$hours = floor($seconds / 3600);
$minutes = floor(($seconds / 60) % 60);
$seconds = $seconds % 60;
$formatted_time = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);

// Fetch highest score
$sql_highest_score = "SELECT MAX(score) AS highest_score FROM game_stats WHERE user_id = ?";
$stmt_highest = $conn->prepare($sql_highest_score);
$stmt_highest->bind_param("i", $user_id);
$stmt_highest->execute();
$result_highest = $stmt_highest->get_result();
$highest_score = $result_highest->fetch_assoc()['highest_score'];

// Fetch score history
$sql_history = "SELECT score, total_time_played FROM game_stats WHERE user_id = ?";
$stmt_history = $conn->prepare($sql_history);
$stmt_history->bind_param("i", $user_id);
$stmt_history->execute();
$result_history = $stmt_history->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Go Back Button -->
    <button class="back-button" onclick="goBack()">Back</button>

    <!-- Outer box for profile page -->
    <div class="box2">
        <div class="profile-container">
            <!-- Title at the top -->
            <h2>User Profile</h2>
            
            <!-- User's circular profile image -->
            <div class="profile-image">
                <img src="images/profile im.jpg" alt="User Image">
            </div>

            <!-- Profile details with transparent boxes -->
            <div class="profile-details">
                <div class="detail-box">
                    <p><strong>User Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                </div>
                <div class="detail-box">
                    <p><strong>Total Play Time:</strong> <?php echo $formatted_time; ?></p>
                </div>
                <div class="detail-box">
                    <p><strong>Total Score:</strong> <?php echo $stats['total_score']; ?></p>
                </div>
                <div class="detail-box">
                <p><strong>Highest Score:</strong> <?php echo $highest_score; ?></p>
                </div>
            </div>

            <!-- Score History -->
            <h3>Score History</h3>
            <table id="scoreTable">
            <thead>
        <tr>
            <th onclick="sortTable(0)">Score</th>
            <th onclick="sortTable(1)">Time Played (HH:MM:SS)</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result_history->fetch_assoc()) { 
            $timePlayed = $row['total_time_played'];
            $hours = floor($timePlayed / 3600);
            $minutes = floor(($timePlayed % 3600) / 60);
            $seconds = $timePlayed % 60;
            $formattedTime = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
        ?>
            <tr>
                <td><?php echo $row['score']; ?></td>
                <td><?php echo $formattedTime; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    function sortTable(columnIndex) {
        const table = document.getElementById("scoreTable");
        const rows = Array.from(table.rows).slice(1); // Exclude the header row

        // Determine sort direction: ascending or descending
        let isAscending = table.getAttribute("data-sort-direction") !== "asc";
        table.setAttribute("data-sort-direction", isAscending ? "asc" : "desc");

        // Sort rows based on the selected column
        rows.sort((a, b) => {
            const colA = a.cells[columnIndex].innerText.trim();
            const colB = b.cells[columnIndex].innerText.trim();

            if (columnIndex === 1) {
                // Parse HH:MM:SS into total seconds for time column
                const timeToSeconds = time => {
                    const [h, m, s] = time.split(":").map(Number);
                    return h * 3600 + m * 60 + s;
                };
                return isAscending
                    ? timeToSeconds(colA) - timeToSeconds(colB)
                    : timeToSeconds(colB) - timeToSeconds(colA);
            } else {
                // Numeric comparison for score column
                return isAscending
                    ? colA - colB
                    : colB - colA;
            }
        });

        // Append the sorted rows back to the table
        const tbody = table.tBodies[0];
        tbody.innerHTML = "";
        rows.forEach(row => tbody.appendChild(row));
    }
</script>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
