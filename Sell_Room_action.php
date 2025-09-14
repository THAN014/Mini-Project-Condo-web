<?php
session_start(); // ต้องเรียก session_start() ก่อนเสมอ
include 'ConnectDB.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['User_id'])) {
    // อาจจะแจ้งเตือนเป็น SweetAlert ที่หน้า login ก็ได้
    $_SESSION['alert_type'] = 'warning';
    $_SESSION['alert_message'] = 'กรุณาเข้าสู่ระบบก่อนลงประกาศขายห้อง';
    header('Location: login.php');
    exit();
}

// รับข้อมูลจากฟอร์ม
$Room_number = $_POST['Room_number'];
$price = $_POST['Room_price'];
$size = $_POST['Room_size'];
$floor = $_POST['Room_floor'];
$description = $_POST['description'];
$seller_id = $_SESSION['User_id'];
$Picture = $_FILES['Picture']['name']; //get file name , type name

// เตรียมคำสั่ง SQL
$stmt = $conn->prepare("INSERT INTO room_db (Room_number, Room_price, Room_size, Room_floor, description, Status, Seller_id, Picture) 
                       VALUES (?, ?, ?, ?, ?, 'Empty', ? , ?)");
$stmt->bind_param("siiisis", $Room_number, $price, $size, $floor, $description, $seller_id , $Picture);

// ลอง Execute และตรวจสอบผลลัพธ์
if ($stmt->execute()) {
    // ถ้าสำเร็จ: เก็บข้อความสำเร็จลง Session
    $_SESSION['alert_type'] = 'success';
    $_SESSION['alert_message'] = 'ลงประกาศขายห้องของคุณเรียบร้อยแล้ว!';
    move_uploaded_file($_FILES['Picture']['tmp_name'],'img/Condo_img/'.$Picture); //move file to destinaged path
} else {
    // ถ้าไม่สำเร็จ: เก็บข้อความข้อผิดพลาดลง Session
    $_SESSION['alert_type'] = 'error';
    $_SESSION['alert_message'] = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $stmt->error;
}

// ปิด statement และ connection
$stmt->close();
$conn->close();

// สั่งให้เบราว์เซอร์กลับไปที่หน้าหลัก (หรือหน้าที่คุณต้องการ)
header('Location: index.php');
exit(); // สั่งหยุดการทำงานของสคริปต์ทันทีหลัง redirect
?>