<?php
session_start();
include 'ConnectDB.php';

if (!isset($_SESSION['User_id'])) {
    die("กรุณาเข้าสู่ระบบก่อนดูประวัติการจอง <a href='login.php'>เข้าสู่ระบบ</a>");
}

$user_id = $_SESSION['User_id'];

// ดึงข้อมูลการจองของผู้ใช้
$sqlrent = "SELECT r.*, r.Room_id AS Room_number, Room_price, Room_size, Room_floor 
        FROM reserve r
        JOIN room_db u ON r.Room_id = u.Room_id
        WHERE r.user_id = ?
";

$stmt = $conn->prepare($sqlrent);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ประวัติการจอง</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #999;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>
    <h2>ประวัติการจองของคุณ (<?= $_SESSION['Username']; ?>)</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ชื่อห้อง</th>
                <th>ราคา</th>
                <th>ขนาด (ตร.ม.)</th>
                <th>ชั้น</th>
                <th>ชื่อผู้จอง</th>
                <th>เบอร์โทร</th>
                <th>อีเมล</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['Room_id']; ?></td>
                    <td><?= number_format($row['Room_price']); ?> บาท</td>
                    <td><?= $row['Room_size']; ?></td>
                    <td><?= $row['Room_floor']; ?></td>
                    <td><?= htmlspecialchars($row['Fullname']); ?></td>
                    <td><?= htmlspecialchars($row['Phone']); ?></td>
                    <td><?= htmlspecialchars($row['Email']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>คุณยังไม่เคยจองห้องใด ๆ</p>
    <?php endif; ?>

    <p><a href="index.php">← กลับไปหน้าหลัก</a></p>
</body>

</html>