<?php
include 'ConnectDB.php';
session_start();
?>


<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <title>Chonburi Condo - ค้นหาคอนโดในชลบุรี</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="เลือกซื้อขายคอนโดคุณภาพในทำเลที่ดีที่สุดของจังหวัดชลบุรี" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="globals.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">Chonburi Condo</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNavbar">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="#buy">ซื้อคอนโด</a></li>
          <li class="nav-item"><a class="nav-link" href="Sell_room.php">ขายคอนโด</a></li>
          <li class="nav-item"><a class="nav-link" href="#about">เกี่ยวกับเรา</a></li>
          <li class="nav-item"><a class="nav-link" href="#contact">ติดต่อ</a></li>
        </ul>
        <div class="d-flex align-items-center">
          <?php if (isset($_SESSION['User_id'])):
            $_SESSION['Role']; ?>

            <?php if ($_SESSION['Role'] === 'Admin'): ?>
              <span class="me-2"><a class="nav-link" href="manage_room.php">ไปที่ Dashboard</a></span>
            <?php endif; ?>

            <span class="me-2"><a class="nav-link" href="user_reserved.php">ประวัติการจอง</a></span>
            <span class="me-2"><a class="nav-link" href="user_purchase.php">ประวัติการซื้อ</a></span>
            <span class="me-2">สวัสดี, <?= htmlspecialchars($_SESSION['Username']); ?></span>
            <a href="logout.php" class="btn btn-outline-secondary btn-sm">ออกจากระบบ</a>
          <?php else: ?>
            <a href="login.php" class="btn btn-outline-primary btn-sm me-2">เข้าสู่ระบบ</a>
            <a href="register.php" class="btn btn-primary btn-sm">สมัครสมาชิก</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>



  <!-- Hero Section -->

  <section class="py-5 text-center bg-white">
    <div class="container">
      <h2 class="display-5 fw-bold mb-3">ค้นหาคอนโดในชลบุรี</h2>
      <p class="lead mb-4">เลือกซื้อขายคอนโดคุณภาพในทำเลที่ดีที่สุดของจังหวัดชลบุรี</p>
      <form class="row g-2 justify-content-center">
        <div class="col-md-3">
          <select id="area-select" name="area" class="form-select">
            <option value="">เลือกพื้นที่</option>
            <option value="pattaya">พัทยา</option>
            <option value="jomtien">จอมเทียน</option>
            <option value="naklua">นาเกลือ</option>
            <option value="sriracha">ศรีราชา</option>
            <option value="laem-chabang">แหลมฉบัง</option>
          </select>
        </div>
        <div class="col-md-3">
          <select id="bedroom-select" name="bedrooms" class="form-select">
            <option value="">จำนวนห้องนอน</option>
            <option value="studio">สตูดิโอ</option>
            <option value="1">1 ห้องนอน</option>
            <option value="2">2 ห้องนอน</option>
            <option value="3">3 ห้องนอน</option>
            <option value="4+">4+ ห้องนอน</option>
          </select>
        </div>
        <div class="col-md-3">
          <input type="text" id="min-price" name="min-price" class="form-control" placeholder="ราคาต่ำสุด (บาท)">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-dark w-100">ค้นหา</button>
        </div>
      </form>
    </div>
  </section>

  <!-- Featured Condos Section -->
  <section class="py-5 bg-white">
    <div class="container">
      <h2 class="mb-3">คอนโดแนะนำ</h2>
      <p class="mb-4">คอนโดคุณภาพในทำเลที่ดีที่สุดของชลบุรี</p>

      <div class="mb-4">
        <div class="btn-group" role="group" aria-label="ตัวกรองคอนโด">
          <button type="button" class="btn btn-outline-dark active">ทั้งหมด</button>
          <button type="button" class="btn btn-outline-dark">สตูดิโอ</button>
          <button type="button" class="btn btn-outline-dark">ห้องนอน</button>
          <button type="button" class="btn btn-outline-dark">หรูหรา</button>
          <button type="button" class="btn btn-outline-dark">ติดทะเล</button>
        </div>
      </div>

      <div class="row g-4" id="condo-list">
        <?php
        $result = $conn->query("SELECT * FROM room_db");
        $condos = [];
        while ($row = $result->fetch_assoc()) {
          $condos[] = $row;
        }
        foreach ($condos as $i => $row):
        ?>
          <div class="col-md-4 condo-card" style="<?= $i >= 3 ? 'display:none;' : '' ?>">
            <div class="card h-100 shadow-sm">
              <?php if (!empty($row['Picture'])): ?>
                <img src="img/Condo_img/<?= htmlspecialchars($row['Picture']) ?>" class="card-img-top" alt="ภาพห้อง <?= htmlspecialchars($row['Room_number']) ?>">
              <?php else: ?>
                <img src="img/placeholder.jpg" class="card-img-top" alt="ไม่มีรูปภาพ">
              <?php endif; ?>
              <div class="card-body">
                <?php if ($row['Status'] == 'Empty'): ?>
                  <span class="badge bg-success mb-2">ว่าง</span>
                <?php elseif ($row['Status'] == 'Sold'): ?>
                  <span class="badge bg-danger mb-2">ขายแล้ว</span>
                <?php else: ?>
                  <span class="badge bg-secondary mb-2">ไม่ว่าง</span>
                <?php endif; ?>
                <h5 class="card-title">ห้อง <?= htmlspecialchars($row['Room_number']); ?></h5>
                <h6 class="card-subtitle mb-2 text-muted">฿<?= number_format($row['Room_price']); ?></h6>
                <p class="card-text"><?= htmlspecialchars($row['Room_location'] ?? 'พัทยา'); ?></p>
                <ul class="list-inline mb-2">
                  <li class="list-inline-item"><?= htmlspecialchars($row['Room_bedroom'] ?? '-'); ?> ห้องนอน</li>
                  <li class="list-inline-item"><?= htmlspecialchars($row['Room_bathroom'] ?? '-'); ?> ห้องน้ำ</li>
                  <li class="list-inline-item"><?= htmlspecialchars($row['Room_size'] ?? '-'); ?> ตร.ม.</li>
                </ul>
                <a href="detail.php?Room_id=<?= urlencode($row['Room_id']); ?>" class="btn btn-outline-primary btn-sm">ดูรายละเอียด</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="text-center mt-4">
        <button class="btn btn-outline-secondary" id="loadMoreBtn">โหลดเพิ่มเติม</button>
      </div>
    </div>
  </section>

  <!-- Why Us Section -->
  <section class="py-5">
    <div class="container">
      <h2 class="mb-3">ทำไมต้องเลือกเรา</h2>
      <p class="mb-4">เราให้บริการที่ครบครันและมีคุณภาพสูงสุด</p>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 text-center shadow-sm">
            <div class="card-body">

              <h5 class="card-title">ตรวจสอบคุณภาพ</h5>
              <p class="card-text">เราตรวจสอบคุณภาพของทุกคอนโดก่อนนำเสนอให้ลูกค้า</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 text-center shadow-sm">
            <div class="card-body">

              <h5 class="card-title">บริการครบครัน</h5>
              <p class="card-text">ให้คำปรึกษาตั้งแต่การเลือกซื้อจนถึงการโอนกรรมสิทธิ์</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 text-center shadow-sm">
            <div class="card-body">

              <h5 class="card-title">ปลอดภัย</h5>
              <p class="card-text">การทำธุรกรรมที่ปลอดภัยและโปร่งใสทุกขั้นตอน</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-light pt-5 pb-3 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-3 mb-3">
          <h5>เกี่ยวกับเรา</h5>
          <ul class="list-unstyled">
            <li><a href="#about-company" class="text-light text-decoration-none">เกี่ยวกับบริษัท</a></li>
            <li><a href="#team" class="text-light text-decoration-none">ทีมงาน</a></li>
            <li><a href="#news" class="text-light text-decoration-none">ข่าวสาร</a></li>
          </ul>
        </div>
        <div class="col-md-3 mb-3">
          <h5>บริการ</h5>
          <ul class="list-unstyled">
            <li><a href="#buy-condo" class="text-light text-decoration-none">ซื้อคอนโด</a></li>
            <li><a href="#sell-condo" class="text-light text-decoration-none">ขายคอนโด</a></li>
            <li><a href="#valuation" class="text-light text-decoration-none">ประเมินราคา</a></li>
          </ul>
        </div>
        <div class="col-md-3 mb-3">
          <h5>ช่วยเหลือ</h5>
          <ul class="list-unstyled">
            <li><a href="#faq" class="text-light text-decoration-none">คำถามที่พบบ่อย</a></li>
            <li><a href="#contact-us" class="text-light text-decoration-none">ติดต่อเรา</a></li>
            <li><a href="#support" class="text-light text-decoration-none">สนับสนุน</a></li>
          </ul>
        </div>
        <div class="col-md-3 mb-3">
          <h5>ติดต่อ</h5>
          <address>
            <div>โทร: 038-123-456</div>
            <div>อีเมล: info@chonburicondo.com</div>
            <div>ที่อยู่: ชลบุรี</div>
          </address>
        </div>
      </div>
      <div class="text-center mt-4">
        <small>© 2024 Chonburi Condo. สงวนลิขสิทธิ์.</small>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Show 3 more condos per click
    document.getElementById('loadMoreBtn').addEventListener('click', function() {
      const cards = document.querySelectorAll('.condo-card');
      let shown = 0;
      cards.forEach(card => {
        if (card.style.display !== 'none') shown++;
      });
      let count = 0;
      for (let i = shown; i < cards.length && count < 3; i++, count++) {
        cards[i].style.display = '';
      }
      // Hide button if all are shown
      if (shown + count >= cards.length) {
        this.style.display = 'none';
      }
    });
  </script>
  <?php
  // ตรวจสอบว่ามีข้อความแจ้งเตือนใน session หรือไม่
  if (isset($_SESSION['alert_message']) && isset($_SESSION['alert_type'])) {
    $alert_message = $_SESSION['alert_message'];
    $alert_type = $_SESSION['alert_type'];

    // แสดงผล SweetAlert ด้วย JavaScript
    echo "<script>
            Swal.fire({
                icon: '{$alert_type}',
                title: 'แจ้งเตือน',
                text: '{$alert_message}',
                confirmButtonText: 'ตกลง'
            });
        </script>";

    // ล้างค่า session หลังจากแสดงผลแล้ว เพื่อไม่ให้แสดงซ้ำ
    unset($_SESSION['alert_message']);
    unset($_SESSION['alert_type']);
  }
  ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
        <?php
        // ตรวจสอบก่อนว่ามี session การแจ้งเตือนอยู่หรือไม่
        $alert_data = null;
        if (isset($_SESSION['booking_status'])) {
            $alert_data = $_SESSION['booking_status'];
            unset($_SESSION['booking_status']); // ล้างค่าทิ้ง
        } elseif (isset($_SESSION['purchase_status'])) {
            $alert_data = $_SESSION['purchase_status'];
            unset($_SESSION['purchase_status']); // ล้างค่าทิ้ง
        }

        // ถ้ามีข้อมูลการแจ้งเตือน, ให้สร้าง script สำหรับแสดงผล
        if ($alert_data):
    ?>
        // แปลงข้อมูลจาก PHP array เป็น JavaScript object
        const alertData = <?= json_encode($alert_data) ?>;

        // แสดง SweetAlert
        Swal.fire({
            icon: alertData.status,
            title: alertData.title,
            // ใช้ html หรือ text ขึ้นอยู่กับข้อมูลที่ส่งมา
            text: alertData.message || null,
            html: alertData.html || null,
            confirmButtonText: 'ตกลง'
        });
    <?php endif; ?>
    </script>
</body>

</html>
</body>

</html>