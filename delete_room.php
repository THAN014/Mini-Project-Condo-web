<?php
session_start();
include 'ConnectDB.php';

// ✅ ตรวจสอบว่าเป็น admin
if (!isset($_SESSION['User_id']) || $_SESSION['Role'] !== 'Admin') {
    die("คุณไม่มีสิทธิ์เข้าถึงหน้านี้ <a href='login.php'>เข้าสู่ระบบ</a>");
}

$Room_id = $_GET['Room_id'] ?? 0;

if ($Room_id) {
    $stmt = $conn->prepare("DELETE FROM room_db WHERE Room_id=?");
    $stmt->bind_param("i", $Room_id);
    $stmt->execute();
}

header("Location: manage_room.php"); // กลับไปหน้า Dashboard
exit;
