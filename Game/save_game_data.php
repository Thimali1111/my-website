<?php
// Include the database connection file
require_once 'db_connection.php';

// Start the session and verify user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["error" => "User not logged in."]);
    exit;
}

// Check if data is sent via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Decode JSON data from the AJAX request
    $data = json_decode(file_get_contents('php://input'), true);

    // Retrieve and validate user score and time
    $score = isset($data['score']) ? intval($data['score']) : 0;
    $total_time = isset($data['total_time']) ? intval($data['total_time']) : 0;

    // Get the logged-in user ID from the session
    $user_id = $_SESSION['user_id'];

    // Prepare the SQL query to insert or update game stats
    $stmt = $conn->prepare("
        INSERT INTO game_stats (user_id, score, total_time_played)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE
        score = score + VALUES(score),
        total_time_played = total_time_played + VALUES(total_time_played)
    ");
    $stmt->bind_param("iii", $user_id, $score, $total_time);

    // Execute the query and send an appropriate response
    if ($stmt->execute()) {
        http_response_code(200); // OK
        echo json_encode(["message" => "Game data saved successfully."]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Failed to save game data.", "details" => $stmt->error]);
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
