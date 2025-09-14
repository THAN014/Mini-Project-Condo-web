<?php
session_start();
include 'ConnectDB.php';

// ✅ ตรวจสอบว่าเป็น admin
if (!isset($_SESSION['User_id']) || $_SESSION['Role'] !== 'Admin') {
    die("คุณไม่มีสิทธิ์เข้าถึงหน้านี้ <a href='login.php'>เข้าสู่ระบบ</a>");
}

// รับค่าจากฟอร์ม
$Room_id = $_POST['Room_id'];
$Room_number = $_POST['Room_number'];
$Room_price = $_POST['Room_price'];
$Room_size = $_POST['Room_size'];
$Room_floor = $_POST['Room_floor'];
$description = $_POST['description'];
$Status = $_POST['Status'];

// รับชื่อไฟล์รูปเก่าจาก hidden input ในฟอร์ม
$Picture_old = $_POST['Picture_old']; 

// ตรวจสอบว่ามีการอัปโหลดไฟล์ใหม่หรือไม่
$Picture_new = $_FILES['Picture']['name'];

if ($Picture_new != "") {
    // ถ้ามีไฟล์ใหม่ถูกอัปโหลด
    $Picture = $Picture_new; // กำหนดให้ตัวแปร $Picture เป็นชื่อไฟล์ใหม่

    // ✅ สร้าง path เต็มของไฟล์เก่าและตรวจสอบว่าไฟล์มีอยู่จริงก่อนลบ
    $old_image_path = 'img/Condo_img/' . $Picture_old;
    if (file_exists($old_image_path)) {
        unlink($old_image_path); // ลบไฟล์รูปเก่า
    }

    // ย้ายไฟล์รูปใหม่ไปเก็บใน folder
    move_uploaded_file($_FILES['Picture']['tmp_name'], 'img/Condo_img/' . $Picture);
    
} else {
    // ถ้าไม่มีการอัปโหลดไฟล์ใหม่ ให้ใช้ชื่อไฟล์เดิม
    $Picture = $Picture_old;
}

// อัปเดตข้อมูลลงฐานข้อมูล
$stmt = $conn->prepare("UPDATE room_db SET Room_number=?, Room_price=?, Room_size=?, Room_floor=?, description=?, Status=?, Picture=? WHERE Room_id=?");
// bind_param ประเภทข้อมูล d = double (ทศนิยม), i = integer (เลขจำนวนเต็ม), s = string (ข้อความ)
$stmt->bind_param("sdissssi", $Room_number, $Room_price, $Room_size, $Room_floor, $description, $Status, $Picture, $Room_id);
$stmt->execute();

// ส่งกลับไปหน้าจัดการห้อง
$_SESSION['status_alert'] = [
    'status' => 'success',
    'title' => 'อัปเดตสำเร็จ!',
    'message' => 'ข้อมูลห้องถูกอัปเดตเรียบร้อยแล้ว'
];
header("Location: manage_room.php");
exit;