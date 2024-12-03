<?php
// Include database connection
require_once 'db_connection.php';

// Fetch top 10 users with their highest scores
$sql_leaderboard = "
    SELECT u.name, MAX(gs.score) AS max_score
    FROM game_stats gs
    JOIN users u ON gs.user_id = u.id
    GROUP BY u.id
    ORDER BY max_score DESC
    LIMIT 10
";

$result = $conn->query($sql_leaderboard);

// Create an array to store leaderboard data
$leaderboard = [];
$rank = 1;
while ($row = $result->fetch_assoc()) {
    $leaderboard[] = [
        'rank' => $rank++,
        'name' => $row['name'],
        'score' => $row['max_score']
    ];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($leaderboard);
$conn->close();
?>
