<?php
session_start();
include 'ConnectDB.php';

// ✅ ตรวจสอบว่าเป็น admin
if (!isset($_SESSION['User_id']) || $_SESSION['Role'] !== 'Admin') {
    die("คุณไม่มีสิทธิ์เข้าถึงหน้านี้ <a href='login.php'>เข้าสู่ระบบ</a>");
}

$Room_id = $_POST['Room_id'];
$Room_number = $_POST['Room_number'];
$Room_price = $_POST['Room_price'];
$Room_size = $_POST['Room_size'];
$Room_floor = $_POST['Room_floor'];
$description = $_POST['description'];
$Status = $_POST['Status'];

$stmt = $conn->prepare("UPDATE room_db SET room_number=?, Room_price=?, Room_size=?, Room_floor=?, description=?, Status=? WHERE Room_id=?");
$stmt->bind_param("siiissi", $room_number, $Room_price, $Room_size, $Room_floor, $description, $Status, $Room_id);
$stmt->execute();

header("Location: manage_room.php");
exit;
