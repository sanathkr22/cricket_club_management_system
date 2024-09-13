<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // If admin is not logged in, redirect to admin login page
    header("Location: admin.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'goatclub');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users for dropdown
$query = "SELECT id, name FROM users";
$result = $conn->query($query);
$users = $result->fetch_all(MYSQLI_ASSOC);

// Initialize variables to store user statistics
$user_id = "";
$matches = "";
$runs = "";
$sixes = "";
$fours = "";
$highest_score = "";
$wickets = "";
$five_wicket_hauls = "";
$best_figure = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission to update player statistics
    // Retrieve form data
    $user_id = $_POST['user_id'];
    $matches = $_POST['matches'];
    $runs = $_POST['runs'];
    $sixes = $_POST['sixes'];
    $fours = $_POST['fours'];
    $highest_score = $_POST['highest_score'];
    $wickets = $_POST['wickets'];
    $five_wicket_hauls = $_POST['five_wicket_hauls'];
    $best_figure = $_POST['best_figure'];

    // Update player statistics
    $query = "UPDATE player_stats SET matches = ?, runs = ?, sixes = ?, fours = ?, highest_score = ?, wickets = ?, five_wicket_hauls = ?, best_figure = ? WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiiiiisi", $matches, $runs, $sixes, $fours, $highest_score, $wickets, $five_wicket_hauls, $best_figure, $user_id);
    $stmt->execute();
    $stmt->close();

    // Provide a notification that details have been updated
    echo "<script>alert('Player statistics updated successfully!');</script>";
} elseif (isset($_GET['user_id'])) {
    // Fetch statistics for the selected user
    $user_id = $_GET['user_id'];
    $query = "SELECT * FROM player_stats WHERE user_id = $user_id";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $matches = $row['matches'];
        $runs = $row['runs'];
        $sixes = $row['sixes'];
        $fours = $row['fours'];
        $highest_score = $row['highest_score'];
        $wickets = $row['wickets'];
        $five_wicket_hauls = $row['five_wicket_hauls'];
        $best_figure = $row['best_figure'];
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(to right, #fafafa, #eaeaea); /* Gradient background */
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 600px; /* Reduced max-width */
        margin: 50px auto;
        padding: 20px;
        background: #fff;
        border-radius: 10px; /* Increased border radius */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    form {
        margin-bottom: 20px;
    }
    label {
        display: block;
        margin-bottom: 5px;
    }
    input[type="number"],
    input[type="text"],
    select {
        width: calc(100% - 22px); /* Reduced width */
        padding: 12px; /* Increased padding */
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 16px; /* Increased font size */
    }
    select {
        width: 100%; /* Set width to 100% */
        background-color: #f5f5f5; /* Light gray background */
    }
    button {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    button:hover {
        background-color: #0056b3;
    }
    .logout-link {
        text-align: center;
        margin-top: 20px;
    }
    .logout-link a {
        color: #007bff;
        text-decoration: none;
    }
</style>

</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="user_id">Select User:</label>
            <select name="user_id" id="user_id">
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo $user['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <!-- Input fields for player statistics -->
            <label for="matches">Matches:</label>
            <input type="number" id="matches" name="matches" value="<?php echo $matches; ?>" required>
            <label for="runs">Runs:</label>
            <input type="number" id="runs" name="runs" value="<?php echo $runs; ?>" required>
            <label for="sixes">Sixes:</label>
            <input type="number" id="sixes" name="sixes" value="<?php echo $sixes; ?>" required>
            <label for="fours">Fours:</label>
            <input type="number" id="fours" name="fours" value="<?php echo $fours; ?>" required>
            <label for="highest_score">Highest Score:</label>
            <input type="number" id="highest_score" name="highest_score" value="<?php echo $highest_score; ?>" required>
            <label for="wickets">Wickets:</label>
            <input type="number" id="wickets" name="wickets" value="<?php echo $wickets; ?>" required>
            <label for="five_wicket_hauls">5 Wicket Hauls:</label>
            <input type="number" id="five_wicket_hauls" name="five_wicket_hauls" value="<?php echo $five_wicket_hauls; ?>" required>
            <label for="best_figure">Best Figure:</label>
            <input type="text" id="best_figure" name="best_figure" value="<?php echo $best_figure; ?>" required>
            <button type="submit">Update Stats</button>
        </form>
        <div class="logout-link">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
