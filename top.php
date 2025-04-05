<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "dbuser";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT user, exp FROM user_pass ORDER BY exp DESC LIMIT 10"; // Get top 10
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #222;
            color: white;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .leaderboard {
            max-width: 500px;
            margin: 50px auto;
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
        }
        .rank {
            font-size: 20px;
            font-weight: bold;
        }
        .gold { color: gold; }
        .silver { color: silver; }
        .bronze { color: #cd7f32; }
        .player {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        .player:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="leaderboard">
        <h1>🏆 Leaderboard</h1>
        <?php
        if ($result->num_rows > 0) {
            $rank = 1;
            while ($row = $result->fetch_assoc()) {
                $user = htmlspecialchars($row['user']);
                $exp = $row['exp'];
                
                // Assign colors for top 3
                $rankClass = "";
                if ($rank == 1) $rankClass = "gold";
                elseif ($rank == 2) $rankClass = "silver";
                elseif ($rank == 3) $rankClass = "bronze";
                
                echo "<div class='player'>
                        <span class='rank $rankClass'>#$rank</span> 
                        <span>$user</span> 
                        <span>$exp EXP</span>
                      </div>";
                $rank++;
            }
        } else {
            echo "<p>No players found.</p>";
        }
        ?>
    </div>
</body>
</html>
