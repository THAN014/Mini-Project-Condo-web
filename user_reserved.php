<?php
session_start();
include 'ConnectDB.php';

if (!isset($_SESSION['User_id'])) {
    header('Location: login.php'); // เปลี่ยนจาก die() เป็น redirect เพื่อ UX ที่ดีกว่า
    exit();
}

$user_id = $_SESSION['User_id'];

// ***ปรับปรุง SQL Query เพื่อให้ได้ข้อมูลครบถ้วนตาม UI ใหม่***
// คุณอาจจะต้องเพิ่มคอลัมน์เหล่านี้ในตารางของคุณ:
// - room_db: Room_name, Bedrooms, Bathrooms, Image_url
// - reserve: status, check_in_date, check_out_date
$sql = "SELECT 
            u.Room_id,
            u.Room_number,
            u.Room_price, 
            u.Room_size, 
            u.Room_floor,
            u.Picture
        FROM reserve r
        JOIN room_db u ON r.Room_id = u.Room_id
        WHERE r.User_id = ?
        ORDER BY r.reserve_id DESC"; // เรียงจากรายการล่าสุดก่อน

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// ฟังก์ชันสำหรับแสดง Badge ของสถานะ
function getStatusBadge($status) {
    switch ($status) {
        case 'confirmed':
            return '<span class="badge bg-success">ยืนยันการจองแล้ว</span>';
        case 'pending':
            return '<span class="badge bg-warning text-dark">รอการชำระเงิน</span>';
        case 'cancelled':
            return '<span class="badge bg-danger">ยกเลิกแล้ว</span>';
        default:
            return '<span class="badge bg-secondary">ไม่ระบุ</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <link rel="shortcut icon" href="img/condo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการจอง - Chonburi Condo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f8f9fa;
        }
        .property-card {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            transition: box-shadow 0.3s ease;
        }
        .property-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }
        .property-card img {
            border-radius: 0.5rem 0 0 0.5rem;
            object-fit: cover;
            height: 100%;
        }
        .features-icon {
            color: #6c757d;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Chonburi Condo</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">ซื้อคอนโด</a></li>
                    <li class="nav-item"><a class="nav-link" href="Sell_room.php">ขายคอนโด</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">เกี่ยวกับเรา</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">ติดต่อ</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="mb-4">
            <h2>ประวัติการจอง</h2>
            <p class="text-muted">ประวัติการจองห้องพักทั้งหมดของคุณ</p>
        </div>

        <div class="card card-body mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-dark">ทั้งหมด</button>
                        <button type="button" class="btn btn-outline-secondary">ยืนยันแล้ว</button>
                        <button type="button" class="btn btn-outline-secondary">รอแก้ไข</button>
                        <button type="button" class="btn btn-outline-secondary">ยกเลิก</button>
                    </div>
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="ค้นหาจากชื่อ...">
                </div>
            </div>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card property-card mb-3">
                    <div class="row g-0">
                        <div class="col-md-2">
                            <img src="img/Condo_img/<?= htmlspecialchars($row['Picture']) ?>" class="img-fluid w-100" alt="Room Image">
                        </div>
                        <div class="col-md-10">
                            <div class="card-body d-flex flex-column h-100">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <h5 class="card-title mb-0">ห้อง <?= htmlspecialchars($row['Room_number'] ?? 'N/A') ?></h5>
                                    </div>
                                </div>
                                <h3 class="text-primary fw-bold">฿<?= number_format($row['Room_price']) ?></h3>
                                <div class="d-flex gap-4 text-muted border-top border-bottom py-2 my-2">
                                    <div class="features-icon"><i class="fa-solid fa-bed me-2"></i><?= htmlspecialchars($row['Bedrooms'] ?? '1') ?> ห้องนอน</div>
                                    <div class="features-icon"><i class="fa-solid fa-bath me-2"></i><?= htmlspecialchars($row['Bathrooms'] ?? '1') ?> ห้องน้ำ</div>
                                    <div class="features-icon"><i class="fa-solid fa-ruler-combined me-2"></i><?= htmlspecialchars($row['Room_size']) ?> ตร.ม.</div>
                                    <div class="features-icon"><i class="fa-solid fa-building me-2"></i>ชั้น <?= htmlspecialchars($row['Room_floor']) ?></div>
                                </div>
                                <div class="mt-auto d-flex justify-content-end align-items-center gap-2 pt-3">
                                     <a href="detail.php?Room_id=<?= urlencode($row['Room_id']); ?>" class="text-decoration-none">ดูรายละเอียด</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-folder-open fa-3x text-muted mb-3"></i>
                <h4>คุณยังไม่มีประวัติการจอง</h4>
                <p class="text-muted">ลองค้นหาคอนโดที่น่าสนใจและเริ่มทำการจองได้เลย</p>
                <a href="index.php" class="btn btn-primary mt-2">ค้นหาคอนโด</a>
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-white pt-5 pb-3">
        <div class="container text-center">
             <h5>ต้องการความช่วยเหลือ?</h5>
             <p>ทีมงานของเราพร้อมให้คำปรึกษาเกี่ยวกับกับการเลือกคอนโดของคุณ</p>
             <button class="btn btn-primary">ดูคำถามที่พบบ่อย</button>
             <hr class="my-4">
             <p>&copy; 2025 Chonburi Condo. สงวนลิขสิทธิ์.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>