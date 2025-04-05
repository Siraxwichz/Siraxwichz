<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "dbuser";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = $_SESSION['username'];

// Retrieve user's current EXP and stage from the database
$stmt = $conn->prepare("SELECT exp, stage FROM user_pass WHERE user = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($exp, $stage);
$stmt->fetch();
$stmt->close();

// Close database connection
$conn->close();

// Logic to determine whether the next stage button should be shown
$showStage2 = $exp >= 10 && $stage >= 1;
$showStage3 = $exp >= 20 && $stage >= 2;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Main</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-dark text-white">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <span class="navbar-text">👤 <?php echo $user; ?> | ⚡ EXP: <?php echo $exp; ?> | 🎯 Stage: <?php echo $stage; ?></span>
            <a href="empty.php" class="btn btn-danger mx-2">Logout</a>
        </div>
    </nav>

    <div class="container text-center mt-5">
        <h1>Welcome, <?php echo $user; ?>!</h1>

        <!-- Display buttons for next stages if conditions are met -->
        <?php if ($stage >= 1): ?>
            <a href="stage1.php" class="btn btn-primary mt-3">Start Stage 1</a>
        <?php endif; ?>

        <?php if ($showStage2): ?>
            <a href="stage2.php" class="btn btn-primary mt-3">Start Stage 2</a>
        <?php endif; ?>

        <?php if ($showStage3): ?>
            <a href="stage3.php" class="btn btn-primary mt-3">Start Stage 3</a>
        <?php endif; ?>

        <!-- Show a message if the user hasn't reached the required EXP for next stage -->
        <?php if ($exp < 10 && $stage < 2): ?>
            <p class="mt-3 text-danger">You need more EXP to unlock Stage 2!</p>
        <?php endif; ?>
    </div>
</body>
</html>
