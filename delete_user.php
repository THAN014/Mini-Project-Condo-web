<?php
session_start();
include 'ConnectDB.php';

// เช็คว่าเป็น admin และมี User_id ส่งมาหรือไม่
if (!isset($_SESSION['User_id']) || $_SESSION['Role'] !== 'Admin' || !isset($_GET['User_id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id_to_delete = $_GET['User_id'];

// --- **สำคัญมาก:** ป้องกันไม่ให้ Admin ลบบัญชีของตัวเอง ---
if ($user_id_to_delete == $_SESSION['User_id']) {
    $_SESSION['status_alert'] = [
        'status' => 'error',
        'title' => 'ไม่สามารถลบได้',
        'message' => 'คุณไม่สามารถลบบัญชีของตัวเองได้'
    ];
    header("Location: manage_users.php");
    exit();
}

// ใช้ Prepared Statement เพื่อความปลอดภัย
$stmt = $conn->prepare("DELETE FROM users WHERE User_id = ?");
$stmt->bind_param("i", $user_id_to_delete);

if ($stmt->execute()) {
    $_SESSION['status_alert'] = [
        'status' => 'success',
        'title' => 'ลบสำเร็จ!',
        'message' => 'ผู้ใช้ ID: ' . $user_id_to_delete . ' ถูกลบออกจากระบบแล้ว'
    ];
} else {
    $_SESSION['status_alert'] = [
        'status' => 'error',
        'title' => 'เกิดข้อผิดพลาด',
        'message' => 'ไม่สามารถลบข้อมูลผู้ใช้ได้'
    ];
}

header("Location: manage_user.php");
exit();
?>