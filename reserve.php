<?php
session_start();
include 'ConnectDB.php';

// Accept Room_id from GET or POST
$Room_id = $_GET['Room_id'] ?? $_POST['Room_id'] ?? null;

if (!isset($_SESSION['User_id'])) {
    die("กรุณาเข้าสู่ระบบก่อนจองห้อง <a href='login.php'>เข้าสู่ระบบ</a>");
}

if (!$Room_id) {
    die("ไม่พบรหัสห้อง");
}

$result = $conn->query("SELECT * FROM room_db WHERE Room_id=" . intval($Room_id));
$room = $result->fetch_assoc();

// Adjust status check to match your DB values (Empty, Sold, etc.)
if (!$room || $room['Status'] != 'Empty') {
    die("ไม่สามารถจองห้องนี้ได้");
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>จองห้อง</title>
</head>
<body>
<h2>จองห้อง: <?= htmlspecialchars($room['Room_number']); ?></h2>
<p>ราคา: <?= number_format($room['Room_price']); ?> บาท</p>
<p>ขนาด: <?= htmlspecialchars($room['Room_size']); ?> ตร.ม.</p>

<form method="post" action="reserve_action.php">
    <input type="hidden" name="Room_id" value="<?= $room['Room_id']; ?>">

    <label>ชื่อ-นามสกุล: 
        <input type="text" name="Fullname" required>
    </label><br><br>

    <label>เบอร์โทรศัพท์: 
        <input type="text" name="Phone" required>
    </label><br><br>

    <label>อีเมล: 
        <input type="email" name="Email" required>
    </label><br><br>

    <button type="submit">ยืนยันการจอง</button>
</form>

<p><a href="detail.php?Room_id=<?= $room['Room_id']; ?>">← กลับไปหน้ารายละเอียดห้อง</a></p>
</body>
</html>
