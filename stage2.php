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

$stage2Words = [
    ['word' => 'Obsequious', 'choices' => ['ประจบสอพลอ', 'หยิ่งยโส', 'แข็งแกร่ง', 'ดื้อรั้น', 'สุภาพ'], 'answer' => 'ประจบสอพลอ'],
    ['word' => 'Recalcitrant', 'choices' => ['อ่อนโยน', 'ดื้อรั้น', 'ว่านอนสอนง่าย', 'เงียบสงบ', 'ใจดี'], 'answer' => 'ดื้อรั้น'],
    ['word' => 'Ubiquitous', 'choices' => ['แพร่หลาย', 'หายาก', 'เป็นเอกลักษณ์', 'ล้าสมัย', 'โบราณ'], 'answer' => 'แพร่หลาย'],
    ['word' => 'Evanescent', 'choices' => ['ชั่วคราว', 'ถาวร', 'มั่นคง', 'ยาวนาน', 'ไม่เปลี่ยนแปลง'], 'answer' => 'ชั่วคราว'],
    ['word' => 'Spurious', 'choices' => ['ปลอม', 'แท้จริง', 'จริงใจ', 'เชื่อถือได้', 'ถูกต้อง'], 'answer' => 'ปลอม'],
    ['word' => 'Impetuous', 'choices' => ['ใจร้อน', 'สุขุม', 'รอบคอบ', 'ช้า', 'มั่นคง'], 'answer' => 'ใจร้อน'],
    ['word' => 'Enervate', 'choices' => ['ทำให้อ่อนแรง', 'ทำให้แข็งแรง', 'ทำให้สดชื่น', 'เพิ่มพลัง', 'ฟื้นฟู'], 'answer' => 'ทำให้อ่อนแรง'],
    ['word' => 'Intransigent', 'choices' => ['ไม่ยอมประนีประนอม', 'ยืดหยุ่น', 'อ่อนน้อม', 'ใจกว้าง', 'เข้าใจง่าย'], 'answer' => 'ไม่ยอมประนีประนอม'],
    ['word' => 'Munificent', 'choices' => ['ใจดีมาก', 'ตระหนี่', 'ประหยัด', 'ขี้เหนียว', 'มัธยัสถ์'], 'answer' => 'ใจดีมาก'],
    ['word' => 'Perspicacious', 'choices' => ['ฉลาดหลักแหลม', 'โง่เขลา', 'ไม่เข้าใจ', 'ไร้เดียงสา', 'สับสน'], 'answer' => 'ฉลาดหลักแหลม']
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
    $selectedWord = $stage2Words[$wordIndex];

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
        $stmt = $conn->prepare("UPDATE user_pass SET exp = ?, stage = 3 WHERE user = ?");
        $stmt->bind_param("is", $exp, $user);
        $stmt->execute();
        $stmt->close();

        echo "<script>
                Swal.fire({
                    title: 'Stage Complete!',
                    text: 'You have completed Stage 2 and earned 10 EXP!',
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
    <title>Stage 2</title>
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
        <h1>Stage 2: Match the Word with its Thai Meaning</h1>
        <?php
        $wordIndex = rand(0, 9);
        $randomWord = $stage2Words[$wordIndex];
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
