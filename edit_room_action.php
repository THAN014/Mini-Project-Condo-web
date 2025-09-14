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
$Picture = $_FILES['Picture']['name']; //get file name , type name

$stmt = $conn->prepare("UPDATE room_db SET Room_number=?, Room_price=?, Room_size=?, Room_floor=?, description=?, Status=? , Picture=? WHERE Room_id=?");
$stmt->bind_param("iiiisssi", $Room_number, $Room_price, $Room_size, $Room_floor, $description, $Status , $Picture, $Room_id);
$stmt->execute();
@unlink('img/Condo_img/' .$row['Picture']);//delete old image
            move_uploaded_file($_FILES['Picture']['tmp_name'], 'img/Condo_img/' . $Picture); //เกห็บรูปใหม่

header("Location: manage_room.php");
exit;
