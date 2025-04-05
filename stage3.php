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

$stage3Words = [
    ['word' => 'Mitigate', 'choices' => ['บรรเทา', 'ทำให้แย่ลง', 'ขยาย', 'สรุป', 'เปลี่ยนแปลง'], 'answer' => 'บรรเทา'],
    ['word' => 'Exacerbate', 'choices' => ['ทำให้แย่ลง', 'ทำให้ดีขึ้น', 'สร้างใหม่', 'ยกเลิก', 'ตรวจสอบ'], 'answer' => 'ทำให้แย่ลง'],
    ['word' => 'Prevalent', 'choices' => ['แพร่หลาย', 'หายาก', 'พิเศษ', 'ล้าสมัย', 'ประจำถิ่น'], 'answer' => 'แพร่หลาย'],
    ['word' => 'Comprehensive', 'choices' => ['ครอบคลุม', 'เจาะจง', 'แคบ', 'พื้นฐาน', 'สั้น'], 'answer' => 'ครอบคลุม'],
    ['word' => 'Redundant', 'choices' => ['ฟุ่มเฟือย', 'ขาดแคลน', 'จำเป็น', 'สำคัญ', 'ไม่มีความหมาย'], 'answer' => 'ฟุ่มเฟือย'],
    ['word' => 'Imminent', 'choices' => ['ใกล้เข้ามา', 'ไกลออกไป', 'เป็นไปไม่ได้', 'ไม่แน่นอน', 'ไม่มีวันเกิดขึ้น'], 'answer' => 'ใกล้เข้ามา'],
    ['word' => 'Conducive', 'choices' => ['เอื้ออำนวย', 'ขัดขวาง', 'เป็นไปไม่ได้', 'เป็นอันตราย', 'ไม่เกี่ยวข้อง'], 'answer' => 'เอื้ออำนวย'],
    ['word' => 'Feasible', 'choices' => ['เป็นไปได้', 'เป็นไปไม่ได้', 'ฝันกลางวัน', 'ต้องห้าม', 'ไม่แน่นอน'], 'answer' => 'เป็นไปได้'],
    ['word' => 'Coherent', 'choices' => ['สอดคล้อง', 'ไม่เชื่อมโยง', 'แตกแยก', 'ไม่สมเหตุสมผล', 'ขัดแย้ง'], 'answer' => 'สอดคล้อง'],
    ['word' => 'Empirical', 'choices' => ['เชิงประจักษ์', 'สมมติฐาน', 'เป็นทฤษฎี', 'ไม่แน่นอน', 'จินตนาการ'], 'answer' => 'เชิงประจักษ์']
];

include 'stage_template.php';
?>
