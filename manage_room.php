<?php
session_start();
include 'ConnectDB.php';

// ✅ เช็คว่าเป็น admin หรือไม่
if (!isset($_SESSION['User_id']) || $_SESSION['Role'] !== 'Admin') {
    die("คุณไม่มีสิทธิ์เข้าถึงหน้านี้ <a href='login.php'>เข้าสู่ระบบ</a>");
}

$admin_id = $_SESSION['User_id'];
$admin_query = $conn->prepare("SELECT Admin_Picture FROM users WHERE User_id = ?");
$admin_query->bind_param("i", $admin_id);
$admin_query->execute();
$admin_result = $admin_query->get_result()->fetch_assoc();


$admin_query->close();
// ✅ ดึงข้อมูลสรุปสำหรับ Stat Cards
$stats_query = "
    SELECT
        (SELECT COUNT(*) FROM reserve) AS total_reservations,
        SUM(CASE WHEN Status = 'reserve' THEN 1 ELSE 0 END) AS pending_rooms,
        SUM(CASE WHEN Status = 'Sold' THEN Room_price ELSE 0 END) AS total_revenue,
        COUNT(*) AS total_rooms,
        SUM(CASE WHEN Status = 'Empty' THEN 1 ELSE 0 END) AS available_rooms
    FROM
        room_db;
";

$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

// ดึงข้อมูลสำหรับตาราง 
$result = $conn->query("SELECT r.*, usr.Username AS seller_name 
                        FROM room_db r
                        LEFT JOIN users usr ON r.Seller_id = usr.User_id
                        ORDER BY r.Room_id DESC");

// ✅ สร้าง Array สำหรับกำหนดสี Badge ของสถานะห้อง
$status_classes = [
    'Empty' => 'bg-success',
    'Sold' => 'bg-danger',
    'reserve' => 'bg-warning text-dark' // เพิ่มสถานะจอง
];
$status_names = [
    'Empty' => 'ว่าง',
    'Sold' => 'ขายแล้ว',
    'reserve' => 'จองแล้ว'
];


?>
<!DOCTYPE html>
<html lang="th">

<head>
    <link rel="shortcut icon" href="img/condo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Chonburi Condo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            /* ✅ ปรับปรุง: เปลี่ยนสีพื้นหลังให้ดูสบายตา */
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 280px;
            min-height: 100vh;
            background-color: #212529;
        }

        .sidebar .nav-link {
            color: #adb5bd;
            font-size: 1rem;
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease-in-out;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: #343a40;
            color: #fff;
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

        /* ✅ ปรับปรุง: ทำให้ตารางดูดีขึ้น */
        .table th {
            font-weight: 500;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <nav class="sidebar flex-shrink-0 p-3 text-white">
            <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <i class="bi bi-building-fill-gear fs-2 me-2"></i>
                <span class="fs-4 fw-bold">Chonburi Condo</span>
            </a>
            <hr>
            <p class="text-secondary small">เมนูหลัก</p>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-1">
                    <a href="manage_room.php" class="nav-link active" aria-current="page"><i class="bi bi-grid-fill"></i> ภาพรวม / จัดการห้อง</a>
                </li>
                <!-- <li class="nav-item mb-1">
                    <a href="reserve_detail.php" class="nav-link text-white"><i class="bi bi-journal-text"></i> การจอง</a>
                </li> -->
                <li class="nav-item mb-1">
                    <a href="manage_user.php" class="nav-link text-white"><i class="bi bi-people-fill"></i> จัดการผู้ใช้</a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="img/Admin_img/<?= ($admin_result['Admin_Picture']) ?>" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong><?= htmlspecialchars($_SESSION['Username']) ?></strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                    <li><a class="dropdown-item" href="#">ตั้งค่า</a></li>
                    <li><a class="dropdown-item" href="#">โปรไฟล์</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="logout.php">ออกจากระบบ</a></li>
                </ul>
            </div>
        </nav>

        <div class="main-content p-3 p-md-4">
            <header class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
                <div class="d-flex align-items-center">
                    <h2 class="h4 mb-0 fw-bold">ภาพรวมระบบ</h2>
                </div>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">ออกจากระบบ</a>
            </header>

            <div class="row g-4 mb-4">
            </div>

            <div class="card">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">จัดการห้องคอนโด</h5>
                    <a href="add_room.php" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle-fill me-1"></i> เพิ่มห้องใหม่
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>ห้อง</th>
                                    <th>ราคา</th>
                                    <th>ขนาด (ตร.ม.)</th>
                                    <th>ชั้น</th>
                                    <th>ผู้ขาย</th>
                                    <th class="text-center">สถานะ</th>
                                    <th class="text-center">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $row['Room_id']; ?></td>
                                            <td class="fw-bold"><?= htmlspecialchars($row['Room_number']); ?></td>
                                            <td><?= number_format($row['Room_price']); ?></td>
                                            <td><?= htmlspecialchars($row['Room_size']); ?></td>
                                            <td><?= htmlspecialchars($row['Room_floor']); ?></td>
                                            <td><?= $row['seller_name'] ?: '<span class="text-muted">ระบบ</span>'; ?></td>
                                            <td class="text-center">
                                                <?php
                                                $status = $row['Status'];
                                                $class = $status_classes[$status] ?? 'bg-secondary';
                                                $name = $status_names[$status] ?? 'ไม่ระบุ';
                                                echo "<span class=\"badge $class\">$name</span>";
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="edit_room.php?Room_id=<?= $row['Room_id']; ?>" class="btn btn-warning btn-sm" title="แก้ไข">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <a href="delete_room.php?Room_id=<?= $row['Room_id']; ?>" class="btn btn-danger btn-sm btn-delete" data-room-id="<?= $row['Room_id'] ?>" title="ลบ">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </a>
                                                <?php if ($row['Status'] === 'reserve'): ?>
                                                    | <a href="reserve_detail.php?Room_id=<?= $row['Room_id']; ?>">👤 ดูผู้จอง</a>
                                                <?php elseif ($row['Status'] === 'Sold'): ?>
                                                    | <a href="purchase_detail.php?Room_id=<?= $row['Room_id']; ?>">💰 ดูผู้ซื้อ</a>
                                                <?php endif; ?>
                                            </td>

                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">ยังไม่มีข้อมูลห้องในระบบ</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // 1. Script สำหรับแจ้งเตือนเมื่อมี status alert จาก session (เช่น หลังแก้ไข/เพิ่มข้อมูล)
        <?php if (isset($_SESSION['status_alert'])): ?>
            Swal.fire({
                icon: '<?= $_SESSION['status_alert']['status'] ?>',
                title: '<?= $_SESSION['status_alert']['title'] ?>',
                text: '<?= $_SESSION['status_alert']['message'] ?>',
                timer: 2000,
                showConfirmButton: false
            });
            <?php unset($_SESSION['status_alert']); ?>
        <?php endif; ?>

        // 2. Script สำหรับยืนยันการลบ
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // หยุดการทำงานของลิงก์ปกติ

                const roomId = this.getAttribute('data-room-id');
                const deleteUrl = this.href;

                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: `คุณต้องการลบห้อง ID: ${roomId} ใช่หรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'ใช่, ลบเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ถ้าผู้ใช้กดยืนยัน ให้ไปที่ลิงก์สำหรับลบ
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    </script>
</body>

</html>