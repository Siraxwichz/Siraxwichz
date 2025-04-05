<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartoony Meme Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f0f0;
            font-family: 'Comic Sans MS', cursive, sans-serif;
            text-align: center;
            padding: 50px;
        }

        .meme {
            width: 80%;
            margin: 20px auto;
        }

        .cartoon-container {
            background-color: #ffcc00;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .cartoon-container h1 {
            font-size: 3em;
            color: #ff5733;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 15px 32px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            margin: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        .meme img {
            max-width: 100%;
            height: auto;
        }

        .rainbow-text {
            font-size: 3em;
            font-weight: bold;
            animation: rainbow 2s linear infinite, changeFont 2s infinite;
        }

        @keyframes rainbow {
            0% { color: red; }
            14% { color: orange; }
            28% { color: yellow; }
            42% { color: green; }
            57% { color: blue; }
            71% { color: indigo; }
            85% { color: violet; }
            100% { color: red; }
        }

        @keyframes changeFont {
            0% { font-family: 'Comic Sans MS', cursive, sans-serif; }
            25% { font-family: 'Arial', sans-serif; }
            50% { font-family: 'Verdana', sans-serif; }
            75% { font-family: 'Georgia', serif; }
            100% { font-family: 'Comic Sans MS', cursive, sans-serif; }
        }
    </style>
</head>
<body>

<div class="cartoon-container">
    <h1 class="rainbow-text">nigga login ก่อน</h1>
    <p>ออกทำไม nigga</p>
    
    <div class="meme">
        <img src="pic/meme.png" alt="Cartoony Meme">
    </div>

    <div>
        <p>click the button left or right button</p>
        <a href="index.php"><button>Register</button></a>
        <a href="index.php"><button>Login</button></a>
    </div>
</div>

</body>
</html>
