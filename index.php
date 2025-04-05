<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "dbuser";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$alert_type = "";
$redirect = "";

// Register Process
if (isset($_POST['register'])) {
    $new_user = trim($_POST['new_username']);
    $new_pass = password_hash(trim($_POST['new_password']), PASSWORD_DEFAULT);

    if (!empty($new_user) && !empty($_POST['new_password'])) {
        $check_stmt = $conn->prepare("SELECT user FROM user_pass WHERE user = ?");
        $check_stmt->bind_param("s", $new_user);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $message = "Username already taken!";
            $alert_type = "error";
        } else {
            $stmt = $conn->prepare("INSERT INTO user_pass (user, pass, exp, stage) VALUES (?, ?, 0, 1)");
            $stmt->bind_param("ss", $new_user, $new_pass);
            if ($stmt->execute()) {
                $message = "Registration successful!";
                $alert_type = "success";
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}

// Login Process
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT pass FROM user_pass WHERE user = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['username'] = $username;
                $message = "Login successful!";
                $alert_type = "success";
                $redirect = "gamemain.php"; 
            } else {
                $message = "Invalid credentials!";
                $alert_type = "error";
            }
        } else {
            $message = "Invalid credentials!";
            $alert_type = "error";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        body {
            background: linear-gradient(135deg, #1a1a1a, #333);
            color: white;
            font-family: 'Arial', sans-serif;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            height: 100vh;
            gap: 20px;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 25px;
            border-radius: 15px;
            width: 100%;
            max-width: 350px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn {
            font-size: 1.1rem;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #ff6f61;
            transform: scale(1.05);
        }
        .btn-success:hover {
            background-color: #28a745;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Login Form -->
    <img src="pic/R (1).gif" alt="Sad Image">
    <div class="form-container animate__animated animate__fadeInLeft">
        <h2>Login</h2>
        <form method="post">
            <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>
    </div>

    <!-- Register Form -->
    <div class="form-container animate__animated animate__fadeInRight">
        <h2>Register</h2>
        <form method="post">
            <input type="text" name="new_username" class="form-control mb-3" placeholder="New Username" required>
            <input type="password" name="new_password" class="form-control mb-3" placeholder="New Password" required>
            <button type="submit" name="register" class="btn btn-success w-100">Register</button>
        </form>
    </div>
</div>

<!-- SweetAlert Script -->
<?php if (!empty($message)): ?>
<script>
    Swal.fire({
        title: "<?php echo ($alert_type == 'success') ? 'Success!' : 'Error!'; ?>",
        text: "<?php echo $message; ?>",
        icon: "<?php echo $alert_type; ?>",
    }).then(() => {
        <?php if (!empty($redirect)): ?>
            window.location.href = "<?php echo $redirect; ?>";
        <?php endif; ?>
    });
</script>
<?php endif; ?>

</body>
</html>
