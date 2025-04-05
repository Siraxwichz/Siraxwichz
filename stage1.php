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

$user = $_SESSION['username'];
$stmt = $conn->prepare("SELECT exp, stage FROM user_pass WHERE user = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($exp, $stage);
$stmt->fetch();
$stmt->close();

$stage1Words = [
    ['word' => 'Abstruse', 'choices' => ['ชัดเจน', 'ยุ่งยาก', 'ง่าย', 'ซับซ้อน', 'เข้าใจง่าย'], 'answer' => 'ยุ่งยาก'],
    ['word' => 'Eloquent', 'choices' => ['พูดจาดี', 'พูดไม่ชัด', 'น่าเบื่อ', 'ไร้สาระ', 'พูดเก่ง'], 'answer' => 'พูดเก่ง'],
    ['word' => 'Ephemeral', 'choices' => ['ถาวร', 'ยาวนาน', 'ชั่วคราว', 'ตลอดไป', 'สั้น'], 'answer' => 'ชั่วคราว'],
    ['word' => 'Supercilious', 'choices' => ['อ่อนน้อม', 'หยิ่ง', 'ใจกว้าง', 'ขี้เกียจ', 'ใจดี'], 'answer' => 'หยิ่ง'],
    ['word' => 'Sycophant', 'choices' => ['ผู้นำ', 'ผู้ติดตาม', 'ผู้ฝึกฝน', 'ผู้ลอกเลียนแบบ', 'ผู้สนับสนุน'], 'answer' => 'ผู้ลอกเลียนแบบ'],
    ['word' => 'Obfuscate', 'choices' => ['กระจ่าง', 'ทำให้สับสน', 'ทำให้ชัดเจน', 'ทำให้เข้าใจง่าย', 'ทำให้โปร่งใส'], 'answer' => 'ทำให้สับสน'],
    ['word' => 'Recalcitrant', 'choices' => ['เชื่อฟัง', 'ดื้อรั้น', 'ขี้เกียจ', 'มองโลกในแง่ดี', 'อ่อนน้อม'], 'answer' => 'ดื้อรั้น'],
    ['word' => 'Nefarious', 'choices' => ['ดี', 'ชั่วร้าย', 'มั่นคง', 'น่ารัก', 'อบอุ่น'], 'answer' => 'ชั่วร้าย'],
    ['word' => 'Pernicious', 'choices' => ['เป็นอันตราย', 'ปลอดภัย', 'ดี', 'ช่วยเหลือ', 'ปกติ'], 'answer' => 'เป็นอันตราย'],
    ['word' => 'Lugubrious', 'choices' => ['สุขสันต์', 'เศร้าโศก', 'ตื่นเต้น', 'เครียด', 'มีความสุข'], 'answer' => 'เศร้าโศก']
];

if (!isset($_SESSION['wrong_answers'])) {
    $_SESSION['wrong_answers'] = 0;
}
if (!isset($_SESSION['correct_answers'])) {
    $_SESSION['correct_answers'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userAnswer = $_POST['answer'];
    $wordIndex = $_POST['word_index'];
    $selectedWord = $stage1Words[$wordIndex];

    if ($userAnswer === $selectedWord['answer']) {
        $_SESSION['correct_answers'] += 1;
        echo "<script>
                Swal.fire({
                    title: 'Correct!',
                    text: 'You answered correctly!',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
              </script>";
    } else {
        $_SESSION['wrong_answers'] += 1;

        if ($_SESSION['wrong_answers'] >= 3) {
            header("Location: sad.php");
            exit();
        } else {
            echo "<script>
                    Swal.fire({
                        title: 'Wrong!',
                        text: 'Oops! This is wrong attempt " . $_SESSION['wrong_answers'] . "!',
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);
                    });
                  </script>";
        }
    }

    if ($_SESSION['correct_answers'] == 10) {
        $exp += 10;
        $stmt = $conn->prepare("UPDATE user_pass SET exp = ?, stage = 2 WHERE user = ?");
        $stmt->bind_param("is", $exp, $user);
        $stmt->execute();
        $stmt->close();

        echo "<script>
                Swal.fire({
                    title: 'Stage Complete!',
                    text: 'You have completed Stage 1 and earned 10 EXP!',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href = 'gamemain.php';
                });
              </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stage 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .btn-answer {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border-radius: 12px;
            padding: 12px 30px;
            margin: 10px;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-answer:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .btn-answer:active {
            background-color: #004085;
            transform: scale(0.98);
        }
    </style>
</head>
<body class="bg-dark text-white">
    <div class="container text-center mt-5">
        <h1>Stage 1: Match the Word with its Thai Meaning</h1>
        <?php
        $wordIndex = rand(0, 9);
        $randomWord = $stage1Words[$wordIndex];
        ?>
        <p><strong>English Word: <?php echo $randomWord['word']; ?></strong></p>
        
        <form method="post">
            <input type="hidden" name="word_index" value="<?php echo $wordIndex; ?>">
            <?php
            shuffle($randomWord['choices']);
            foreach ($randomWord['choices'] as $choice) {
                echo "<button type='submit' name='answer' value='$choice' class='btn-answer'>$choice</button>"; 
            }
            ?>
        </form>
    </div>
</body>
</html>
