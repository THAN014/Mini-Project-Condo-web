<?php
session_start();
include 'ConnectDB.php';

// ✅ ตรวจสอบสิทธิ์ admin
if (!isset($_SESSION['User_id']) || $_SESSION['Role'] !== 'Admin') {
    die("คุณไม่มีสิทธิ์เข้าถึงหน้านี้ <a href='login.php'>เข้าสู่ระบบ</a>");
}

$Room_id = $_GET['Room_id'] ?? 0;

// ดึงข้อมูลห้อง + ผู้ซื้อ
$sql = "SELECT u.Room_number AS Room_number, u.Room_price, u.Room_size, u.Room_floor, usr.Username 
        FROM purchases p
        JOIN room_db u ON p.Room_id = u.Room_id
        JOIN users usr ON p.User_id = usr.User_id
        WHERE p.Room_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $Room_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("ไม่พบข้อมูลการซื้อสำหรับห้องนี้ <a href='manage_room.php'>กลับ</a>");
}
$res = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <title>ข้อมูลการซื้อห้อง - Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 280px;
            min-height: 100vh;
            background-color: #212529;
        }
        .sidebar .nav-link { color: #adb5bd; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background-color: #343a40; color: #fff; }
        .sidebar .nav-link .bi { margin-right: 0.75rem; }
        .main-content { flex: 1; }
        .card { border: none; box-shadow: 0 0.5rem 1rem rgba(0,0,0,.05); }
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease-in-out; }
            .sidebar.show { transform: translateX(0); }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="main-content p-3 p-md-4">
            <header class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
                <div class="d-flex align-items-center">
                    <button class="btn btn-light d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                        <i class="bi bi-list"></i>
                    </button>
                    <h2 class="h4 mb-0 fw-bold">ข้อมูลการซื้อห้อง</h2>
                </div>
                <div class="d-flex align-items-center">
                     <div class="d-flex align-items-center">
                        <img src="https://via.placeholder.com/40" class="rounded-circle" alt="Admin avatar">
                        <div class="ms-2 d-none d-md-block">
                            <span class="fw-bold">Admin</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-white py-3">
                           <h5 class="mb-0 fw-bold"><i class="bi bi-house-door-fill me-2"></i>ข้อมูลห้อง</h5>
                        </div>
                        <div class="card-body">
                           <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">ชื่อห้อง/หมายเลขห้อง:</span>
                                    <span class="fw-bold"><?= htmlspecialchars($res['Room_number']); ?></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">ราคา:</span>
                                    <span class="fw-bold fs-5 text-success"><?= number_format($res['Room_price']); ?> บาท</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">ขนาด:</span>
                                    <span class="fw-bold"><?= htmlspecialchars($res['Room_size']); ?> ตร.ม.</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">ชั้น:</span>
                                    <span class="fw-bold"><?= htmlspecialchars($res['Room_floor']); ?></span>
                                </li>
                           </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-white py-3">
                           <h5 class="mb-0 fw-bold"><i class="bi bi-person-fill me-2"></i>ข้อมูลผู้ซื้อ</h5>
                        </div>
                         <div class="card-body">
                           <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">ชื่อผู้ใช้ (Username):</span>
                                    <span class="fw-bold"><?= htmlspecialchars($res['Username']); ?></span>
                                </li>
                           </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="manage_room.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> กลับไปหน้า Dashboard
                </a>
                <!-- <a href="#" class="btn btn-success">
                    <i class="bi bi-check-circle-fill"></i> ยืนยันการจอง
                </a>
                <a href="#" class="btn btn-danger">
                    <i class="bi bi-x-circle-fill"></i> ยกเลิกการจอง
                </a> -->
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
