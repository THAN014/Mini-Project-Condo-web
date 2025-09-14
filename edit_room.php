<?php
session_start();
include 'ConnectDB.php';

// ✅ Logic เดิมของคุณยังคงอยู่ครบถ้วนและทำงานได้ดี
if (!isset($_SESSION['User_id']) || $_SESSION['Role'] !== 'Admin') {
    die("คุณไม่มีสิทธิ์เข้าถึงหน้านี้ <a href='login.php'>เข้าสู่ระบบ</a>");
}

// ตรวจสอบว่ามี Room_id ส่งมาหรือไม่
if (!isset($_GET['Room_id'])) {
    die("ไม่พบรหัสห้องที่ต้องการแก้ไข");
}

$Room_id = $_GET['Room_id'];
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขห้องคอนโด - Chonburi Condo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 800px;
        }

        .card {
            border-radius: 0.75rem;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="text-center mb-4">
            <h2>แก้ไขข้อมูลห้องคอนโด</h2>
            <p class="text-muted">แก้ไขรายละเอียดของห้อง <?= htmlspecialchars($room_db['Room_name'] ?? 'รหัส: ' . $room_db['Room_number']); ?></p>
        </div>

        <div class="card p-4 p-md-5">
            <form method="post" action="edit_room_action.php" enctype="multipart/form-data">
                <input type="hidden" name="Room_id" value="<?= $room_db['Room_id']; ?>">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="room_name" class="form-label">ชื่อคอนโด/โครงการ</label>
                        <input type="text" class="form-control" id="room_name" name="Room_name" value="<?= htmlspecialchars($room_db['Room_name'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="room_number" class="form-label">ห้องเลขที่</label>
                        <input type="text" class="form-control" id="room_number" name="Room_number" value="<?= htmlspecialchars($room_db['Room_number']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="room_price" class="form-label">ราคา (บาท)</label>
                        <input type="number" class="form-control" id="room_price" name="Room_price" value="<?= $room_db['Room_price']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="room_size" class="form-label">ขนาด (ตร.ม.)</label>
                        <input type="number" step="0.01" class="form-control" id="room_size" name="Room_size" value="<?= $room_db['Room_size']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="room_floor" class="form-label">ชั้น</label>
                        <input type="number" class="form-control" id="room_floor" name="Room_floor" value="<?= $room_db['Room_floor']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">สถานะ</label>
                        <select class="form-select" id="status" name="Status">
                            <option value="Empty" <?= $room_db['Status'] == 'Empty' ? 'selected' : ''; ?>>ว่าง</option>
                            <option value="reserve" <?= $room_db['Status'] == 'reserve' ? 'selected' : ''; ?>>ถูกจอง</option>
                            <option value="Sold" <?= $room_db['Status'] == 'Sold' ? 'selected' : ''; ?>>ขายแล้ว</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">รายละเอียด</label>
                        <textarea class="form-control" id="description" name="description" rows="5"><?= htmlspecialchars($room_db['Description'] ?? ''); ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">รูปภาพเดิม</label>
                        <div>
                            <?php if (!empty($room_db['Picture'])): ?>
                                <img src="img/Condo_img/<?= htmlspecialchars($room_db['Picture']); ?>" alt="รูปภาพเดิม" class="img-fluid rounded border" style="max-height: 200px;">
                            <?php else: ?>
                                <p class="text-muted">ไม่มีรูปภาพ</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="picture" class="form-label">อัปโหลดรูปภาพใหม่ (ถ้าต้องการเปลี่ยน)</label>
                        <input type="file" class="form-control" id="picture" name="Picture">
                        <div class="form-text">ปล่อยว่างไว้หากไม่ต้องการเปลี่ยนรูปภาพ</div>

                        <input type="hidden" name="Picture_old" value="<?= htmlspecialchars($room_db['Picture']) ?>">
                        <input type="hidden" name="Room_id" value="<?= htmlspecialchars($room_db['Room_id']) ?>">
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="manage_room.php" class="btn btn-secondary"><i class="fa-solid fa-xmark me-2"></i>ยกเลิก</a>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-2"></i>บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>