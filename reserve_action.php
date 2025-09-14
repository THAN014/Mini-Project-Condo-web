<?php
session_start();
include 'ConnectDB.php';

// ตรวจสอบว่า User ล็อกอินหรือยัง
if (!isset($_SESSION['User_id'])) {
    // ส่งข้อความ Error กลับไปใน Session
    $_SESSION['booking_status'] = [
        'status' => 'error',
        'title' => 'เกิดข้อผิดพลาด',
        'message' => 'กรุณาเข้าสู่ระบบก่อนทำการจองห้อง'
    ];
    header('Location: login.php'); // ถ้ายังไม่ล็อกอิน ให้ไปหน้า login
    exit();
}

// รับค่าจาก Form
$Room_id = $_POST['Room_id'];
$User_id = $_SESSION['User_id'];
$Username = $_SESSION['Username'];
$Fullname = $_POST['Fullname'];
$Phone = $_POST['Phone'];
$Email = $_POST['Email'];
$Room_number = $_POST['Room_number'];

// --- ใช้ Prepared Statements เพื่อความปลอดภัย ---

// 1. ตรวจสอบสถานะห้องว่ายังว่าง (Empty) หรือไม่
$stmt_check = $conn->prepare("SELECT Status FROM room_db WHERE Room_id = ?");
$stmt_check->bind_param("i", $Room_id);
$stmt_check->execute();
$result = $stmt_check->get_result();
$room = $result->fetch_assoc();

if (!$room || $room['Status'] !== 'Empty') {
    // ถ้าห้องไม่ว่าง ให้ส่งข้อความ Error กลับไปแล้ว redirect
    $_SESSION['booking_status'] = [
        'status' => 'error',
        'title' => 'ไม่สามารถจองได้',
        'message' => 'ขออภัย, ห้องนี้ถูกจองหรือขายไปแล้ว!'
    ];
    header('Location: index.php'); // กลับไปหน้าหลัก
    exit();
}

// 2. ถ้าห้องว่าง, ดำเนินการบันทึกการจอง
$stmt_insert = $conn->prepare("INSERT INTO reserve (Room_id, User_id, Username, Fullname, Phone, Email) VALUES (?, ?, ?, ?, ?, ?)");
$stmt_insert->bind_param("iissss", $Room_id, $User_id, $Username, $Fullname, $Phone, $Email);

if ($stmt_insert->execute()) {
    // 3. อัปเดตสถานะห้องเป็น 'reserve'
    $stmt_update = $conn->prepare("UPDATE room_db SET Status = 'reserve' WHERE Room_id = ?");
    $stmt_update->bind_param("i", $Room_id);
    $stmt_update->execute();

    // ตั้งค่า Session สำหรับแจ้งเตือน SweetAlert ว่าสำเร็จ
    $_SESSION['booking_status'] = [
        'status' => 'success',
        'title' => 'การจองสำเร็จ!',
        'message' => 'คุณได้จองห้อง ' . htmlspecialchars($Room_number) . ' เรียบร้อยแล้ว'
    ];
} else {
    // หากการบันทึกผิดพลาด
    $_SESSION['booking_status'] = [
        'status' => 'error',
        'title' => 'เกิดข้อผิดพลาด',
        'message' => 'ไม่สามารถบันทึกข้อมูลการจองได้'
    ];
}

// ไม่ว่าจะสำเร็จหรือล้มเหลว ให้ Redirect กลับไปหน้าหลัก
header('Location: index.php');
exit();
?>