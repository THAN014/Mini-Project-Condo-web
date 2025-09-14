<?php
session_start();
include 'ConnectDB.php';

$Room_id = $_GET['Room_id'];
$result = $conn->query("SELECT * FROM room_db WHERE Room_id='$Room_id'");
$room = $result->fetch_assoc();

if (!$room) {
    echo "<p>ไม่พบห้อง</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>รายละเอียดห้อง <?= htmlspecialchars($room['Room_number']) ?> - Chonburi Condo</title>
    <meta name="description" content="รายละเอียดห้องคอนโด">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Chonburi Condo</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">ซื้อคอนโด</a></li>
        <li class="nav-item"><a class="nav-link" href="Sell_room.php">ขายคอนโด</a></li>
        <li class="nav-item"><a class="nav-link" href="#">เกี่ยวกับเรา</a></li>
        <li class="nav-item"><a class="nav-link" href="#">ติดต่อ</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mb-4">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($room['Room_number']) ?></li>
    </ol>
  </nav>

  <div class="row g-4">

    <div class="col-lg-6">
      <div class="position-relative">
        <img src="img/Condo_img/<?= htmlspecialchars($room['Picture']) ?>" alt="ภาพห้อง <?= htmlspecialchars($room['Room_id']) ?>" class="img-fluid rounded shadow-sm w-100 mb-3">
        <span class="badge bg-<?= $room['Status'] == 'Empty' ? 'success' : ($room['Status'] == 'Sold' ? 'danger' : 'secondary') ?> position-absolute top-0 start-0 m-3 p-3 fs-6">
          <?php
              if ($room['Status'] == 'Empty') {
                  echo 'ว่าง';
              } elseif ($room['Status'] == 'Sold') {
                  echo 'ขายแล้ว';
              } else {
                  echo 'ไม่ว่าง';
              }
          ?>
        </span>
      </div>
    </div>
    <div class="col-lg-6">
      <h2 class="fw-bold"><?= htmlspecialchars($room['Room_number']) ?></h2>
      <div class="h3 text-primary mb-3">฿<?= number_format($room['Room_price']) ?></div>
      <div class="mb-3 text-muted">
        <i class="bi bi-geo-alt"></i>
        <?= htmlspecialchars($room['Room_location'] ?? 'พัทยา, ชลบุรี') ?>
      </div>
      <div class="row text-center mb-4">
        <div class="col">
          <div class="fw-semibold fs-4"><?= htmlspecialchars($room['Room_bedroom'] ?? '-') ?></div>
          <div class="text-muted">ห้องนอน</div>
        </div>
        <div class="col">
          <div class="fw-semibold fs-4"><?= htmlspecialchars($room['Room_bathroom'] ?? '-') ?></div>
          <div class="text-muted">ห้องน้ำ</div>
        </div>
        <div class="col">
          <div class="fw-semibold fs-4"><?= htmlspecialchars($room['Room_size']) ?></div>
          <div class="text-muted">ตร.ม.</div>
        </div>
      </div>
      <div class="mb-4">
        <?php if ($room['Status'] == 'Empty'): ?>
          <?php if (isset($_SESSION['User_id'])): ?>
            <form method="post" action="reserve.php" class="d-inline">
              <input type="hidden" name="Room_id" value="<?= $room['Room_id']; ?>">
              <button class="btn btn-primary px-4" type="submit">จองห้องนี้</button>
            </form>
            <form method="post" action="buy.php" class="d-inline">
              <input type="hidden" name="Room_id" value="<?= $room['Room_id']; ?>">
              <button class="btn btn-primary px-4" type="submit">ซื้อห้องนี้</button>
            </form>
          <?php else: ?>
            <a href="login.php" class="btn btn-primary px-4">เข้าสู่ระบบเพื่อจอง</a>
          <?php endif; ?>
        <?php else: ?>
          <button class="btn btn-secondary px-4" type="button" disabled>
            ห้องถูก<?= htmlspecialchars($room['Status']) ?>แล้ว
          </button>
        <?php endif; ?>
        <a href="index.php" class="btn btn-outline-secondary ms-2 px-4">← กลับหน้าหลัก</a>
      </div>
    </div>
  </div>

  <div class="row g-4 mt-2">
    <div class="col-lg-8">
      <div class="card mb-4">
        <div class="card-body">
          <h4 class="card-title mb-3">รายละเอียด</h4>
          <p class="card-text"><?= nl2br(htmlspecialchars($room['Room_detail'] ?? 'ไม่มีรายละเอียดเพิ่มเติม')) ?></p>
          <h5 class="mt-4 mb-3">จุดเด่น</h5>
          <div class="row row-cols-2 row-cols-md-3 g-2">
            <div class="col"><span class="badge bg-info text-dark w-100 py-2">เฟอร์นิเจอร์ครบ</span></div>
            <div class="col"><span class="badge bg-info text-dark w-100 py-2">เครื่องใช้ไฟฟ้า</span></div>
            <div class="col"><span class="badge bg-info text-dark w-100 py-2">ระเบียงวิวทะเล</span></div>
            <div class="col"><span class="badge bg-info text-dark w-100 py-2">ที่จอดรถ</span></div>
            <div class="col"><span class="badge bg-info text-dark w-100 py-2">ระบบรักษาความปลอดภัย</span></div>
            <div class="col"><span class="badge bg-info text-dark w-100 py-2">สระว่ายน้ำ</span></div>
          </div>
        </div>
      </div>
      <div class="card mb-4">
        <div class="card-body">
          <h4 class="card-title mb-3">สิ่งอำนวยความสะดวก</h4>
          <div class="row row-cols-2 row-cols-md-3 g-2">
            <div class="col"><span class="badge bg-light text-dark border w-100 py-2">ฟิตเนส</span></div>
            <div class="col"><span class="badge bg-light text-dark border w-100 py-2">ซาวน่า</span></div>
            <div class="col"><span class="badge bg-light text-dark border w-100 py-2">รปภ. 24 ชม.</span></div>
            <div class="col"><span class="badge bg-light text-dark border w-100 py-2">ห้องประชุม</span></div>
            <div class="col"><span class="badge bg-light text-dark border w-100 py-2">ใกล้ BTS</span></div>
            <div class="col"><span class="badge bg-light text-dark border w-100 py-2">คอนเซียร์จ</span></div>
          </div>
        </div>
      </div>
      <div class="card mb-4">
        <div class="card-body">
          <h4 class="card-title mb-3">ตำแหน่งที่ตั้ง</h4>
          <div class="mb-2 text-muted">แผนที่แสดงตำแหน่ง</div>
          <div><?= htmlspecialchars($room['Room_location'] ?? 'Sea View Condo Pattaya') ?></div>
          <!-- You can embed a map here if you have coordinates -->
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="card-title">เกี่ยวกับเรา</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="link-secondary">เกี่ยวกับบริษัท</a></li>
            <li><a href="#" class="link-secondary">ทีมงาน</a></li>
            <li><a href="#" class="link-secondary">ข่าวสาร</a></li>
          </ul>
        </div>
      </div>
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="card-title">บริการ</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="link-secondary">ซื้อคอนโด</a></li>
            <li><a href="#" class="link-secondary">ขายคอนโด</a></li>
            <li><a href="#" class="link-secondary">ประเมินราคา</a></li>
          </ul>
        </div>
      </div>
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="card-title">ช่วยเหลือ</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="link-secondary">คำถามที่พบบ่อย</a></li>
            <li><a href="#" class="link-secondary">ติดต่อเรา</a></li>
            <li><a href="#" class="link-secondary">สนับสนุน</a></li>
          </ul>
        </div>
      </div>
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="card-title">ติดต่อ</h5>
          <div>โทร: 038-123-456</div>
          <div>อีเมล: info@chonburicondo.com</div>
          <div>ที่อยู่: ชลบุรี</div>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="bg-dark text-white py-4 mt-5">
  <div class="container text-center">
    <div>© 2024 Chonburi Condo. สงวนลิขสิทธิ์.</div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Optionally add Bootstrap Icons CDN for icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</body>
</html>

