<?php
session_start();
include 'ConnectDB.php';

// 1. ตรวจสอบว่า User ล็อกอินหรือยัง
if (!isset($_SESSION['User_id'])) {
    // ถ้ายังไม่ล็อกอิน ให้ไปหน้า login พร้อมข้อความแจ้งเตือน
    $_SESSION['purchase_status'] = [
        'status' => 'error',
        'title' => 'เกิดข้อผิดพลาด',
        'message' => 'กรุณาเข้าสู่ระบบก่อนทำการซื้อห้อง'
    ];
    header('Location: login.php');
    exit();
}

$Room_id = $_POST['Room_id'];
$buyer_id = $_SESSION['User_id'];

// --- ใช้ Prepared Statements เพื่อความปลอดภัย ---

// 2. ตรวจสอบสถานะห้องและดึงข้อมูล
$stmt_check = $conn->prepare("SELECT Room_number, Room_price, Status FROM room_db WHERE Room_id = ?");
$stmt_check->bind_param("i", $Room_id);
$stmt_check->execute();
$result = $stmt_check->get_result();
$room = $result->fetch_assoc();

// 3. ตรวจสอบว่าห้องสามารถซื้อได้หรือไม่
if (!$room || $room['Status'] !== 'Empty') {
    // ถ้าห้องไม่ว่าง ให้ส่งข้อความ Error กลับไปแล้ว redirect
    $_SESSION['purchase_status'] = [
        'status' => 'error',
        'title' => 'ซื้อไม่สำเร็จ',
        'message' => 'ขออภัย, ห้องนี้ไม่พร้อมสำหรับการซื้อขาย!'
    ];
    header('Location: index.php'); // กลับไปหน้าหลัก
    exit();
}

// 4. ถ้าห้องว่าง, ดำเนินการบันทึกการซื้อ
$stmt_insert = $conn->prepare("INSERT INTO purchases (Room_id, User_id, Room_price) VALUES (?, ?, ?)");
$stmt_insert->bind_param("iid", $Room_id, $buyer_id, $room['Room_price']);

if ($stmt_insert->execute()) {
    // 5. อัปเดตสถานะห้องเป็น 'Sold'
    $stmt_update = $conn->prepare("UPDATE room_db SET Status = 'Sold' WHERE Room_id = ?");
    $stmt_update->bind_param("i", $Room_id);
    $stmt_update->execute();

    // 6. ตั้งค่า Session สำหรับแจ้งเตือน SweetAlert ว่าสำเร็จ
    $_SESSION['purchase_status'] = [
        'status' => 'success',
        'title' => 'ซื้อห้องสำเร็จ!',
        'html' => "คุณได้ซื้อห้อง <b>" . htmlspecialchars($room['Room_number']) . "</b> เรียบร้อยแล้ว<br>ราคา: " . number_format($room['Room_price']) . " บาท"
    ];
} else {
    // หากการบันทึกผิดพลาด
    $_SESSION['purchase_status'] = [
        'status' => 'error',
        'title' => 'เกิดข้อผิดพลาด',
        'message' => 'ไม่สามารถบันทึกข้อมูลการซื้อได้'
    ];
}

// 7. ไม่ว่าจะสำเร็จหรือล้มเหลว ให้ Redirect กลับไปหน้าหลัก
header('Location: index.php');
exit();
?>