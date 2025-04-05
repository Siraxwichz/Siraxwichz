<?php
session_start();
$_SESSION['wrong_answers'] = 0; // Reset wrong answers
$_SESSION['correct_answers'] = 0; // Reset correct answers
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You Lost</title>
    <style>
        body {
            text-align: center;
            background-color: #111;
            color: white;
            font-family: Arial, sans-serif;
            margin-top: 50px;
        }
        img {
            width: 300px;
            height: auto;
            margin-top: 20px;
        }
        .retry {
            background-color: red;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <h1>A level ไม่น่ารอดว่ะ คำง่ายเเค่นี้</h1>
    <img src="pic/sad-meme.gif" alt="Sad Image">
    <br><br>
    <a href="gamemain.php" class="retry">ลองใหม่ไอ้dum</a>
    <source src="sound/galaxy-meme.mp3" type="mp3">
    
</body>
</html>
