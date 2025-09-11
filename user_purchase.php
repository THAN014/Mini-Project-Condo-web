<?php
session_start();
include 'ConnectDB.php';

if (!isset($_SESSION['User_id'])) {
    die("กรุณาเข้าสู่ระบบก่อนดูประวัติการซื้อ <a href='login.php'>เข้าสู่ระบบ</a>");
}

$user_id = $_SESSION['User_id'];

$sqlbuy = "SELECT p.*, p.Room_id AS Room_number, Room_size, Room_floor
        FROM purchases p
        JOIN room_db r ON p.Room_id = r.Room_id
        WHERE p.User_id = ? ";

$stmt = $conn->prepare($sqlbuy);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ประวัติการซื้อ</title>
<style>
table { border-collapse: collapse; width: 100%; margin-top: 20px; }
table, th, td { border: 1px solid #999; padding: 8px; text-align: center; }
th { background-color: #eee; }
</style>
</head>
<body>
<h2>ประวัติการซื้อห้องคอนโดของคุณ (<?= $_SESSION['User_id']; ?>)</h2>

<?php if ($result->num_rows > 0): ?>
<table>
    <tr>
        <th>ชื่อห้อง</th>
        <th>ราคา</th>
        <th>ขนาด</th>
        <th>ชั้น</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['Room_number']; ?></td>
        <td><?= number_format($row['Room_price']); ?> บาท</td>
        <td><?= $row['Room_size']; ?> ตร.ม.</td>
        <td><?= $row['Room_floor']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
<p>คุณยังไม่เคยซื้อห้องคอนโด</p>
<?php endif; ?>

<p><a href="index.php">← กลับไปหน้าหลัก</a></p>
</body>
</html>