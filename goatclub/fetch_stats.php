<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'goatclub');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $query = "SELECT * FROM player_stats WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats = $result->fetch_assoc();
    echo json_encode($stats);
    $stmt->close();
}

$conn->close();
?>
