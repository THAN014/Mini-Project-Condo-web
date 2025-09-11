<?php
session_start();
include 'ConnectDB.php';

if (!isset($_SESSION['User_id'])) {
    die("กรุณาเข้าสู่ระบบก่อนจองห้อง <a href='login.php'>เข้าสู่ระบบ</a>");
}

$Room_id = $_POST['Room_id'];
$User_id = $_SESSION['User_id'];
$Username = $_SESSION['Username'];

$Fullname = $_POST['Fullname'];
$Phone = $_POST['Phone'];
$Email = $_POST['Email'];

// ตรวจสอบว่ายังว่างอยู่ไหม
$check = $conn->query("SELECT Status FROM room_db WHERE Room_id=$Room_id");
$row = $check->fetch_assoc();

if (!$row || $row['Status'] != 'Empty') {
    die("ห้องนี้ถูก {$row['Status']} ไปแล้ว!");
}

// บันทึกการจอง
$stmt = $conn->prepare("INSERT INTO reserve (Room_id, User_id, Username, Fullname, Phone, Email) 
                        VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissss", $Room_id, $User_id, $Username, $Fullname, $Phone, $Email);
$stmt->execute();

// อัพเดตสถานะห้องเป็น Sold
$conn->query("UPDATE room_db SET Status='reserve' WHERE Room_id=$Room_id");
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>การจองสำเร็จ</title>
</head>

<body>
    <h2>จองห้องสำเร็จ!</h2>
    <p>คุณได้จองห้อง <b><?= htmlspecialchars($Room_id) ?></b> เรียบร้อยแล้ว</p>
    <p>ชื่อผู้จอง: <?= htmlspecialchars($Fullname); ?></p>
    <p>เบอร์โทร: <?= htmlspecialchars($Phone); ?></p>
    <p>อีเมล: <?= htmlspecialchars($Email); ?></p>

    <p><a href="index.php">← กลับไปหน้าหลัก</a></p>
</body>

</html>