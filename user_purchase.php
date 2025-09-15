<?php
session_start();
include 'ConnectDB.php';

if (!isset($_SESSION['User_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['User_id'];

// ***ปรับปรุง SQL Query เพื่อให้ได้ข้อมูลครบถ้วนตาม UI ใหม่***
// คุณอาจจะต้องเพิ่มคอลัมน์เหล่านี้ในตาราง room_db ของคุณ: Room_name, Bedrooms, Bathrooms, Image_url
// และอาจจะต้องเพิ่มคอลัมน์ `fee` ในตาราง purchases
$sqlbuy = "SELECT 
            p.Room_id,
            p.Room_price,
            r.Room_number,
            r.Room_size, 
            r.Room_floor,
            r.Picture
        FROM purchases p
        JOIN room_db r ON p.Room_id = r.Room_id
        WHERE p.User_id = ?
        ORDER BY p.purchase_id DESC"; // เรียงจากรายการล่าสุดก่อน

$stmt = $conn->prepare($sqlbuy);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="shortcut icon" href="img/condo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการซื้อ - Chonburi Condo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f8f9fa;
        }
        .purchase-card {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            transition: box-shadow 0.3s ease;
            background-color: #fff;
        }
        .purchase-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }
        .purchase-card img {
            border-radius: 0.5rem 0 0 0.5rem;
            object-fit: cover;
            height: 100%;
        }
        .card-divider {
            border-left: 1px solid #e9ecef;
        }
        .price-section {
            text-align: right;
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
            <h2>ประวัติการซื้อ</h2>
            <p class="text-muted">ประวัติการซื้อห้องคอนโดทั้งหมดของคุณ</p>
        </div>

        <div class="card card-body mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-dark">ทั้งหมด</button>
                        <button type="button" class="btn btn-outline-secondary">ซื้อ</button>
                        <button type="button" class="btn btn-outline-secondary">ขาย</button>
                        <button type="button" class="btn btn-outline-secondary">รอดำเนินการ</button>
                    </div>
                </div>
                <div class="col">
                    <input type="text" class="form-control" placeholder="ค้นหาจากชื่อคอนโด, รหัส...">
                </div>
                 <div class="col-auto">
                     <button class="btn btn-light border"><i class="fa-solid fa-filter me-2"></i>ตัวกรอง</button>
                </div>
                 <div class="col-auto">
                     <button class="btn btn-light border"><i class="fa-solid fa-arrow-down-wide-short me-2"></i>สถานะทั้งหมด</button>
                </div>
            </div>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="purchase-card mb-3">
                    <div class="row g-0">
                        <div class="col-lg-2">
                            <img src="img/Condo_img/<?= htmlspecialchars($row['Picture']) ?>" class="img-fluid w-100" alt="Room Image">
                        </div>

                        <div class="col-lg-7">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <h5 class="card-title mb-0">ห้อง <?= htmlspecialchars($row['Room_number'] ?? 'N/A') ?></h5>
                                    </div>
                                    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">สำเร็จ</span>
                                </div>

                                <div class="row gx-4 gy-2 text-muted small my-3">
                                    <div class="col-6 col-sm-3"><b><?= htmlspecialchars($row['Bedrooms'] ?? '1') ?></b> ห้องนอน</div>
                                    <div class="col-6 col-sm-3"><b><?= htmlspecialchars($row['Bathrooms'] ?? '1') ?></b> ห้องน้ำ</div>
                                    <div class="col-6 col-sm-3"><b><?= htmlspecialchars($row['Room_size']) ?></b> ตร.ม.</div>
                                    <div class="col-6 col-sm-3">ชั้น <b><?= htmlspecialchars($row['Room_floor']) ?></b></div>
                                </div>
                                
                                <div class="bg-light p-2 rounded small">
                                    <div class="row">
                                        <div class="col-6">ค่าธรรมเนียม: ฿<?= number_format($row['fee'] ?? 45000) ?></div>
                                        <div class="col-6">ราคาซื้อขั้นต้น: ฿<?= number_format(($row['Room_price'] ?? 0) - ($row['fee'] ?? 45000)) ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 card-divider">
                             <div class="card-body d-flex flex-column h-100 justify-content-center price-section">
                                <h4 class="fw-bold text-primary mb-2">฿<?= number_format($row['Room_price']) ?></h4>
                                <a href="detail.php?Room_id=<?= urlencode($row['Room_id']); ?>" class="text-decoration-none small mb-3">ดูรายละเอียด</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-folder-open fa-3x text-muted mb-3"></i>
                <h4>คุณยังไม่มีประวัติการซื้อ</h4>
                <p class="text-muted">คอนโดที่คุณซื้อจะปรากฏที่นี่</p>
                <a href="index.php" class="btn btn-primary mt-2">ไปเลือกซื้อคอนโด</a>
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