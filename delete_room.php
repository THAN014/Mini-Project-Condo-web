<?php
session_start();
include 'ConnectDB.php';

// เช็คว่าเป็น admin และมี Room_id ส่งมา
if (!isset($_SESSION['User_id']) || $_SESSION['Role'] !== 'Admin' || !isset($_GET['Room_id'])) {
    header("Location: manage_room.php");
    exit();
}

$Room_id = $_GET['Room_id'];

// --- **สำคัญ** ดึงชื่อไฟล์รูปภาพมาก่อนที่จะลบข้อมูลออกจาก DB ---
$stmt_select = $conn->prepare("SELECT Picture FROM room_db WHERE Room_id = ?");
$stmt_select->bind_param("i", $Room_id);
$stmt_select->execute();
$result = $stmt_select->get_result();
$room = $result->fetch_assoc();

if ($room) {
    $image_to_delete = 'img/Condo_img/' . $room['Picture'];

    // ลบข้อมูลออกจากฐานข้อมูล
    $stmt_delete = $conn->prepare("DELETE FROM room_db WHERE Room_id = ?");
    $stmt_delete->bind_param("i", $Room_id);

    if ($stmt_delete->execute()) {
        // ถ้าลบข้อมูลสำเร็จ ให้ลบไฟล์รูปภาพด้วย
        if (file_exists($image_to_delete)) {
            unlink($image_to_delete);
        }

        // ตั้งค่า session สำหรับ SweetAlert
        $_SESSION['status_alert'] = [
            'status' => 'success',
            'title' => 'ลบสำเร็จ!',
            'message' => 'ห้อง ID: ' . $Room_id . ' ถูกลบออกจากระบบแล้ว'
        ];
    } else {
        $_SESSION['status_alert'] = [
            'status' => 'error',
            'title' => 'เกิดข้อผิดพลาด',
            'message' => 'ไม่สามารถลบข้อมูลห้องได้'
        ];
    }
}

header("Location: manage_room.php");
exit();
?>