<?php
session_start();
include 'ConnectDB.php';

// ✅ ตรวจสอบว่าเป็น admin
if (!isset($_SESSION['User_id']) || $_SESSION['Role'] !== 'Admin') {
    die("คุณไม่มีสิทธิ์เข้าถึงหน้านี้ <a href='login.php'>เข้าสู่ระบบ</a>");
}

$Room_id = $_GET['Room_id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM room_db WHERE Room_id=?");
$stmt->bind_param("i", $Room_id);
$stmt->execute();
$result = $stmt->get_result();
$room_db = $result->fetch_assoc();

if (!$room_db) {
    die("ไม่พบข้อมูลห้อง");
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>แก้ไขห้องคอนโด</title>
</head>

<body>
    <h2>แก้ไขข้อมูลห้องคอนโด</h2>

    <form method="post" action="edit_room_action.php">
        <input type="hidden" name="Room_id" value="<?= $room_db['Room_id']; ?>">

        <label>ชื่อห้อง / โครงการ:
            <input type="text" name="Room_number" value="<?= $room_db['Room_number']; ?>" required>
        </label><br><br>

        <label>ราคา (บาท):
            <input type="number" name="Room_price" value="<?= $room_db['Room_price']; ?>" required>
        </label><br><br>

        <label>ขนาด (ตร.ม.):
            <input type="number" name="Room_size" value="<?= $room_db['Room_size']; ?>" required>
        </label><br><br>

        <label>ชั้น:
            <input type="number" name="Room_floor" value="<?= $room_db['Room_floor']; ?>" required>
        </label><br><br>

        <label>รายละเอียด:
            <textarea name="description" rows="5" cols="40"><?= $room_db['Description']; ?></textarea>
        </label><br><br>

        <label>สถานะ:
            <select name="Status">
                <option value="Empty" <?= $room_db['Status'] == 'Empty' ? 'selected' : ''; ?>>ว่าง</option>
                <option value="reserve" <?= $room_db['Status'] == 'reserve' ? 'selected' : ''; ?>>ถูกจอง</option>
                <option value="Sold" <?= $room_db['Status'] == 'Sold' ? 'selected' : ''; ?>>ขายแล้ว</option>
            </select>
        </label><br><br>

        <button type="submit">บันทึกการแก้ไข</button>
    </form>

    <p><a href="manage_room.php">← กลับไปหน้าจัดการห้อง</a></p>
</body>

</html>