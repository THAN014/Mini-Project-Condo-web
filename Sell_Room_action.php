<?php
session_start();
include 'ConnectDB.php';

if (!isset($_SESSION['User_id'])) {
    die("กรุณาเข้าสู่ระบบก่อนขายห้อง <a href='login.php'>เข้าสู่ระบบ</a>");
}

$Room_number = $_POST['Room_number'];
$price = $_POST['Room_price'];
$size = $_POST['Room_size'];
$floor = $_POST['Room_floor'];
$description = $_POST['description'];
$seller_id = $_SESSION['User_id'];

$stmt = $conn->prepare("INSERT INTO room_db (Room_number, Room_price, Room_size, Room_floor, description, Status, Seller_id) 
                        VALUES ( ?, ?, ?, ?, ?, 'Empty', ?)");
$stmt->bind_param("siiisi", $Room_number, $price, $size, $floor, $description, $seller_id);
$stmt->execute();

echo "ลงประกาศขายห้องเรียบร้อยแล้ว! <a href='index.php'>กลับไปหน้าหลัก</a>";
