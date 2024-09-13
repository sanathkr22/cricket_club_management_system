<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #ffafbd, #ffc3a0); /* Gradient background */
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 10px;
        }
        .stats {
            margin-top: 20px;
        }
        .logout-link {
            text-align: center;
            margin-top: 20px;
        }
        .logout-link a {
            color: #007bff;
            text-decoration: none;
        }
        .back-link {
    display: block;
    text-align: center;
    margin-top: 20px;
    text-decoration: none;
    color: #007bff;
}

.back-link:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <div class="container">
        <?php
        session_start();
        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'goatclub');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch user data
        $user_id = $_SESSION['user_id'];
        $query = "SELECT * FROM users WHERE id = $user_id";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            echo "<h2>Welcome, " . $user_data['name'] . "!</h2>";
            echo "<p>Email: " . $user_data['email'] . "</p>";
            echo "<p>Role: " . $user_data['role'] . "</p>";
        } else {
            echo "<p>Error fetching user data.</p>";
        }

        // Fetch player statistics
        $query = "SELECT * FROM player_stats WHERE user_id = $user_id";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $stats = mysqli_fetch_assoc($result);
            echo "<h3>Player Statistics</h3>";
            echo "<p>Matches: " . $stats['matches'] . "</p>";
            echo "<p>Runs: " . $stats['runs'] . "</p>";
            echo "<p>Sixes: " . $stats['sixes'] . "</p>";
            echo "<p>Fours: " . $stats['fours'] . "</p>";
            echo "<p>Highest Score: " . $stats['highest_score'] . "</p>";
            echo "<p>Wickets: " . $stats['wickets'] . "</p>";
            echo "<p>5 Wicket Hauls: " . $stats['five_wicket_hauls'] . "</p>";
            echo "<p>Best Figure: " . $stats['best_figure'] . "</p>";
        } else {
            echo "<p>No player statistics available.</p>";
        }

        mysqli_close($conn);
        ?>
        <a href="index.html" class="back-link">Back to Home</a>

    </div>
</body>
</html>
