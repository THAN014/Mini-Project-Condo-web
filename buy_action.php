<?php
session_start();
include 'ConnectDB.php';

if (!isset($_SESSION['User_id'])) {
    die("กรุณาเข้าสู่ระบบก่อนซื้อห้อง <a href='login.php'>เข้าสู่ระบบ</a>");
}

$Room_id = $_POST['Room_id'];
$buyer_id = $_SESSION['User_id'];

// ตรวจสอบสถานะห้อง
$check = $conn->query("SELECT * FROM room_db WHERE Room_id=$Room_id");
$room = $check->fetch_assoc();

if (!$room || $room['Status'] != 'Empty') {
    die("ห้องนี้ไม่สามารถซื้อได้");
}

// บันทึกการซื้อ
$stmt = $conn->prepare("INSERT INTO purchases (Room_id, User_id, Room_price) VALUES (?, ?, ?)");
$stmt->bind_param("iid", $Room_id, $buyer_id, $room['Room_price']);
$stmt->execute();

// อัพเดตสถานะห้องเป็น sold
$conn->query("UPDATE room_db SET Status='Sold' WHERE Room_id=$Room_id");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>การซื้อสำเร็จ</title>
</head>
<body>
<h2>ซื้อห้องสำเร็จ!</h2>
<p>คุณได้ซื้อห้อง: <?= $room['Room_number']; ?></p>
<p>ราคา: <?= number_format($room['Room_price']); ?> บาท</p>
<p>วันที่ซื้อ: <?= date("Y-m-d H:i:s"); ?></p>

<p><a href="user_purchase.php">ดูประวัติของคุณ</a></p>
<p><a href="index.php">← กลับไปหน้าหลัก</a></p>
</body>
</html>
