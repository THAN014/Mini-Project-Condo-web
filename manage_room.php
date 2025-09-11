<?php
session_start();
include 'ConnectDB.php';

// ✅ เช็คว่าเป็น admin หรือไม่
if (!isset($_SESSION['User_id']) || $_SESSION['Role'] !== 'Admin') {
    die("คุณไม่มีสิทธิ์เข้าถึงหน้านี้ <a href='login.php'>เข้าสู่ระบบ</a>");
}

$result = $conn->query("SELECT r.*, usr.Username AS seller_name 
                        FROM room_db r
                        LEFT JOIN users usr ON r.Seller_id = usr.User_id
                        ORDER BY r.Room_id DESC");
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Chonburi Condo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #8f8f8fff;
        }

        .sidebar {
            width: 280px;
            min-height: 100vh;
            background-color: #212529;
        }

        .sidebar .nav-link {
            color: #909090ff;
            font-size: 1rem;
            padding: 0.75rem 1.5rem;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: #343a40;
            color: #ecececff;
        }

        .sidebar .nav-link .bi {
            margin-right: 0.75rem;
        }

        .main-content {
            flex: 1;
        }

        .stat-card .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
        }

        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .05);
        }

        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 1045;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            .sidebar.show {
                transform: translateX(0);
            }
        }

        .table-responsive {
            margin-top: 2rem;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <nav class="sidebar flex-shrink-0 p-3 text-white offcanvas-lg offcanvas-start" id="sidebarMenu">
            <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="fs-4 fw-bold">Chonburi Condo</span>
            </a>
            <hr>
            <p class="text-primary small">เมนูหลัก</p>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link active" aria-current="page">
                        <i class="bi bi-grid-fill"></i> ภาพรวม
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white">
                        <i class="bi bi-building-fill"></i> การจอง
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white">
                        <i class="bi bi-house-door-fill"></i> คอนโด
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white">
                        <i class="bi bi-people-fill"></i> ลูกค้า
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white">
                        <i class="bi bi-file-earmark-bar-graph-fill"></i> รายงาน
                    </a>
                </li>
            </ul>
            <hr>
            <ul class="nav nav-pills flex-column">
                <li>
                    <a href="#" class="nav-link text-white">
                        <i class="bi bi-send-fill"></i> ส่งคำสั่ง
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link text-white">
                        <i class="bi bi-gear-fill"></i> ตั้งค่า
                    </a>
                </li>
            </ul>
        </nav>

        <div class="main-content p-3 p-md-4">
            <header class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom text-white">
                <div class="d-flex align-items-center">
                    <button class="btn btn-light d-lg-none me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                        <i class="bi bi-list"></i>
                    </button>
                    <h2 class="h4 mb-0 fw-bold">ภาพรวม</h2>
                </div>
                <div class="d-flex align-items-center">
                    <form class="d-none d-md-flex me-3">
                        <input class="form-control" type="search" placeholder="ค้นหา...">
                    </form>
                    <div class="position-relative me-3">
                        <a href="#" class="text-primary"><i class="bi bi-bell-fill fs-5"></i></a>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6em;">3</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <img src="https://via.placeholder.com/40" class="rounded-circle" alt="Admin avatar">
                        <div class="ms-2 d-none d-md-block">
                            <span class="fw-bold">Admin</span>
                        </div>
                        <a href="logout.php" class="btn btn-outline-secondary btn-sm ms-3">ออกจากระบบ</a>
                    </div>
                </div>
            </header>

            <!-- Stat Cards (can be dynamic later) -->
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-xl-3">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">การจองทั้งหมด</p>
                                <h3 class="fw-bold mb-2">156</h3>
                                <small class="text-success"><i class="bi bi-arrow-up"></i> +12% จากเดือนที่แล้ว</small>
                            </div>
                            <div class="icon-circle bg-primary"><i class="bi bi-journal-check"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">รอยืนยัน</p>
                                <h3 class="fw-bold mb-2">12</h3>
                                <small class="text-warning">ต้องดำเนินการ</small>
                            </div>
                            <div class="icon-circle bg-warning"><i class="bi bi-clock-history"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">รายได้รวม</p>
                                <h3 class="fw-bold mb-2">฿45,600,000</h3>
                                <small class="text-success"><i class="bi bi-arrow-up"></i> +8% จากเดือนที่แล้ว</small>
                            </div>
                            <div class="icon-circle bg-success"><i class="bi bi-cash-stack"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">คอนโดทั้งหมด</p>
                                <h3 class="fw-bold mb-2">24</h3>
                                <small class="text-muted">22 ใช้งานได้</small>
                            </div>
                            <div class="icon-circle" style="background-color: #6f42c1;"><i class="bi bi-buildings-fill"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Room Management Table -->
            <div class="card mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">จัดการห้องคอนโด</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>ชื่อห้อง</th>
                                <th>ราคา</th>
                                <th>ขนาด</th>
                                <th>ชั้น</th>
                                <th>ผู้ขาย</th>
                                <th>สถานะ</th>
                                <th>การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['Room_id']; ?></td>
                                    <td><?= htmlspecialchars($row['Room_number']); ?></td>
                                    <td><?= number_format($row['Room_price']); ?> บาท</td>
                                    <td><?= htmlspecialchars($row['Room_size']); ?> ตร.ม.</td>
                                    <td><?= htmlspecialchars($row['Room_floor']); ?></td>
                                    <td><?= $row['seller_name'] ?: 'ระบบ'; ?></td>
                                    <td>
                                        <?php
                                        if ($row['Status'] == 'Empty') {
                                            echo '<span class="badge bg-success">ว่าง</span>';
                                        } elseif ($row['Status'] == 'Sold') {
                                            echo '<span class="badge bg-danger">ขายแล้ว</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary">ไม่ว่าง</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="edit_room.php?id=<?= $row['Room_id']; ?>">✏️ แก้ไข</a> |
                                        <a href="delete_room.php?id=<?= $row['Room_id']; ?>" onclick="return confirm('ยืนยันลบห้องนี้?');">🗑️ ลบ</a>
                                    </td>

                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-end mb-4">
                <a href="index.php" class="btn btn-outline-secondary">← กลับไปหน้าหลัก</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>