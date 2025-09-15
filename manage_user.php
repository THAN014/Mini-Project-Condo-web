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

// ✅ ดึงข้อมูลผู้ใช้ทั้งหมดจากฐานข้อมูล
$result = $conn->query("SELECT User_id, Username,  Email, Phone, Role FROM users ORDER BY User_id DESC");

// ✅ สร้าง Array สำหรับกำหนดสี Badge ของ Role
$role_classes = [
    'Admin' => 'bg-primary',
    'User' => 'bg-secondary'
];
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <link rel="shortcut icon" href="img/condo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการผู้ใช้ - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: 280px; min-height: 100vh; background-color: #212529; }
        .sidebar .nav-link { color: #adb5bd; font-size: 1rem; padding: 0.75rem 1.5rem; transition: all 0.2s ease-in-out; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background-color: #343a40; color: #fff; }
        .sidebar .nav-link .bi { margin-right: 0.75rem; }
        .main-content { flex: 1; }
        .card { border: none; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .05); }
        .table th { font-weight: 500; }
        .table td, .table th { vertical-align: middle; }
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
                    <a href="manage_room.php" class="nav-link text-white"><i class="bi bi-grid-fill"></i> ภาพรวม / จัดการห้อง</a>
                </li>
                <!-- <li class="nav-item mb-1">
                    <a href="#" class="nav-link text-white"><i class="bi bi-journal-text"></i> การจอง</a>
                </li> -->
                <li class="nav-item mb-1">
                     <a href="manage_users.php" class="nav-link active" aria-current="page"><i class="bi bi-people-fill"></i> จัดการผู้ใช้</a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img  src="img/Admin_img/<?=($admin_result['Admin_Picture']) ?>" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong><?= htmlspecialchars($_SESSION['Username']) ?></strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                    <li><a class="dropdown-item" href="logout.php">ออกจากระบบ</a></li>
                </ul>
            </div>
        </nav>

        <div class="main-content p-3 p-md-4">
            <header class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom">
                <h2 class="h4 mb-0 fw-bold">จัดการผู้ใช้</h2>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">ออกจากระบบ</a>
            </header>
            
            <div class="card">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">รายชื่อผู้ใช้ทั้งหมด</h5>
                    <a href="add_user.php" class="btn btn-primary btn-sm">
                        <i class="bi bi-person-plus-fill me-1"></i> เพิ่มผู้ใช้ใหม่
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>อีเมล</th>
                                    <th>เบอร์โทร</th>
                                    <th class="text-center">สิทธิ์</th>
                                    <th class="text-center">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $row['User_id']; ?></td>
                                            <td class="fw-bold"><?= htmlspecialchars($row['Username']); ?></td>
                                            <td><?= htmlspecialchars($row['Email'] ?: '<span class="text-muted">N/A</span>'); ?></td>
                                            <td><?= htmlspecialchars($row['Phone'] ?: '<span class="text-muted">N/A</span>'); ?></td>
                                            <td class="text-center">
                                                <?php
                                                    $role = $row['Role'];
                                                    $class = $role_classes[$role] ?? 'bg-dark';
                                                    echo "<span class=\"badge $class\">$role</span>";
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="edit_user.php?User_id=<?= $row['User_id']; ?>" class="btn btn-warning btn-sm" title="แก้ไข">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <?php if ($_SESSION['User_id'] != $row['User_id']): ?>
                                                    <a href="delete_user.php?User_id=<?= $row['User_id']; ?>" class="btn btn-danger btn-sm btn-delete" data-user-id="<?= $row['User_id'] ?>" data-user-name="<?= htmlspecialchars($row['Username']) ?>" title="ลบ">
                                                        <i class="bi bi-trash3-fill"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">ยังไม่มีข้อมูลผู้ใช้ในระบบ</td>
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
        // Script สำหรับแจ้งเตือน (เช่น หลัง เพิ่ม/แก้ไข/ลบ)
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

        // Script สำหรับยืนยันการลบผู้ใช้
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                
                const userId = this.getAttribute('data-user-id');
                const userName = this.getAttribute('data-user-name');
                const deleteUrl = this.href;

                Swal.fire({
                    title: 'ยืนยันการลบ',
                    html: `คุณต้องการลบผู้ใช้ <b>${userName}</b> (ID: ${userId}) ใช่หรือไม่?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'ใช่, ลบเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    </script>
</body>
</html>