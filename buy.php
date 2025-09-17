<?php
session_start();
include 'ConnectDB.php';

// Accept Room_id from GET or POST
$Room_id = $_GET['Room_id'] ?? $_POST['Room_id'] ?? null;

if (!isset($_SESSION['User_id'])) {
    die("กรุณาเข้าสู่ระบบก่อนซื้อห้อง <a href='login.php'>เข้าสู่ระบบ</a>");
}

if (!$Room_id) {
    die("ไม่พบรหัสห้อง");
}

// ตรวจสอบว่ายัง available อยู่ไหม
$check = $conn->query("SELECT * FROM room_db WHERE Room_id=" . intval($Room_id));
$room = $check->fetch_assoc();

if (!$room || $room['Status'] != 'Empty') {
    die("ห้องนี้ไม่สามารถซื้อได้");
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="shortcut icon" href="img/condo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สั่งซื้อคอนโด - Chonburi Condo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f8f9fa; }
        .card { border: 1px solid #dee2e6; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
        h5 { font-weight: 700; }
        .condo-selection-card { position: relative; cursor: pointer; transition: border-color .15s ease-in-out; }
        .condo-selection-card.selected { border: 2px solid #0d6efd; }
        .condo-selection-card .price { color: #0d6efd; font-weight: 700; font-size: 1.25rem; }
        .steps-timeline { position: relative; padding-left: 2.5rem; }
        .steps-timeline::before { content: ''; position: absolute; left: 19px; top: 1.5rem; bottom: 1.5rem; width: 2px; background-color: #e9ecef; z-index: 1; }
        .step { position: relative; z-index: 2; margin-bottom: 1.5rem; }
        .step:last-child { margin-bottom: 0; }
        .step-number { width: 40px; height: 40px; background-color: #e9ecef; color: #6c757d; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; }
        .step.active .step-number { background-color: #0d6efd; color: white; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Chonburi Condo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">ซื้อคอนโด</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">ขายคอนโด</a></li>
<!--                     <li class="nav-item"><a class="nav-link" href="#">เกี่ยวกับเรา</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">ติดต่อ</a></li> -->
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold">สั่งซื้อคอนโด</h1>
            <p class="text-muted">กรอกข้อมูลเพื่อสั่งซื้อคอนโดที่คุณต้องการ</p>
        </div>

        <div class="row g-5">
            <div class="col-lg-7">
                <form method="post" action="buy_action.php">
                    <input type="hidden" name="Room_id" value="<?= $room['Room_id']; ?>">
                    <section class="mb-5">
                        <h5>เลือกคอนโด</h5>
                        <label class="card condo-selection-card selected p-2 mb-3">
                            <input type="radio" name="condoSelection" checked disabled>
                            <div class="row g-0 align-items-center">
                                <div class="col-md-4">
                                    <img src="img/Condo_img/<?= htmlspecialchars($room['Picture']) ?>" class="img-fluid rounded" alt="Condo Image">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold"><?= htmlspecialchars($room['Room_number']); ?></h6>
                                        <p class="price mb-2">฿<?= number_format($room['Room_price']); ?></p>
                                        <p class="card-text text-muted mb-1"><small>
                                            <?= htmlspecialchars($room['Room_bedroom'] ?? '-') ?> ห้องนอน ⋅
                                            <?= htmlspecialchars($room['Room_bathroom'] ?? '-') ?> ห้องน้ำ ⋅
                                            <?= htmlspecialchars($room['Room_size'] ?? '-') ?> ตร.ม.
                                        </small></p>
                                        <p class="card-text text-muted"><small><?= htmlspecialchars($room['Room_location'] ?? '-') ?></small></p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </section>

                    <section class="mb-5">
                        <h5>ข้อมูลส่วนตัว</h5>
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="firstName" class="form-label">ชื่อ *</label>
                                        <input type="text" class="form-control" id="firstName" name="firstName" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="lastName" class="form-label">นามสกุล *</label>
                                        <input type="text" class="form-control" id="lastName" name="lastName" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">เบอร์โทรศัพท์ *</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">อีเมล *</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="nationalId" class="form-label">เลขบัตรประชาชน *</label>
                                        <input type="text" class="form-control" id="nationalId" name="nationalId" required placeholder="กรอกหมายเลขบัตรประชาชน 13 หลัก">
                                    </div>
                                    <div class="col-12">
                                        <label for="address" class="form-label">ที่อยู่ *</label>
                                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h5>วิธีการชำระเงิน</h5>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="bankTransfer" value="bankTransfer" checked>
                                    <label class="form-check-label" for="bankTransfer">
                                        <strong>โอนเงินผ่านธนาคาร</strong>
                                        <small class="d-block text-muted">โอนเต็มจำนวนผ่านบัญชีธนาคารของบริษัท</small>
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="installments" value="installments">
                                    <label class="form-check-label" for="installments">
                                        <strong>ผ่อนชำระ</strong>
                                        <small class="d-block text-muted">ผ่อนชำระกับเราโดยตรงตามเงื่อนไขที่เลือก</small>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="bankLoan" value="bankLoan">
                                    <label class="form-check-label" for="bankLoan">
                                        <strong>สินเชื่อธนาคาร</strong>
                                        <small class="d-block text-muted">ยื่นเรื่องขอสินเชื่อกับธนาคารพันธมิตร</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="detail.php?Room_id=<?= $room['Room_id']; ?>" class="btn btn-outline-secondary">← กลับไปหน้ารายละเอียด</a>
                        <button type="submit" class="btn btn-primary px-4">ยืนยันการซื้อ</button>
                    </div>
                </form>
            </div>
            
            <div class="col-lg-5">
                <section class="mb-4">
                    <h5>สรุปคำสั่งซื้อ</h5>
                    <div class="card">
                        <div class="card-body text-center text-muted py-5">
                            <strong><?= htmlspecialchars($room['Room_number']); ?></strong><br>
                            ราคา: <span class="text-primary fw-bold">฿<?= number_format($room['Room_price']); ?></span><br>
                            ขนาด: <?= htmlspecialchars($room['Room_size']); ?> ตร.ม.<br>
                            ชั้น: <?= htmlspecialchars($room['Room_floor']); ?><br>
                            สถานะ: <span class="badge bg-success"><?= ($room['Status'] == 'Empty') ? 'ว่าง' : 'ไม่ว่าง' ?></span>
                        </div>
                    </div>
                </section>

                <section class="mb-4">
                    <h5>ข้อมูลการติดต่อ</h5>
                    <div class="card">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-center">
                                <i class="bi bi-telephone-fill fs-5 me-3 text-primary"></i>
                                <div>
                                    <strong>โทรศัพท์</strong>
                                    <div class="text-muted">038-123-456</div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="bi bi-envelope-fill fs-5 me-3 text-primary"></i>
                                <div>
                                    <strong>อีเมล</strong>
                                    <div class="text-muted">info@chonburicondo.com</div>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="bi bi-geo-alt-fill fs-5 me-3 text-primary"></i>
                                <div>
                                    <strong>ที่อยู่</strong>
                                    <div class="text-muted">ออฟฟิศ จ.ชลบุรี ประเทศไทย</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </section>
                
                <section>
                    <h5>ขั้นตอนการซื้อ</h5>
                    <div class="card">
                        <div class="card-body steps-timeline">
                            <div class="step d-flex align-items-start active">
                                <div class="step-number me-3">1</div>
                                <div>
                                    <strong>กรอกข้อมูลและส่งคำสั่งซื้อ</strong>
                                    <p class="text-muted small">กรอกข้อมูลผู้ซื้อและส่งเอกสารคำขอสินเชื่อเบื้องต้น</p>
                                </div>
                            </div>
                            <div class="step d-flex align-items-start">
                                <div class="step-number me-3">2</div>
                                <div>
                                    <strong>ตรวจสอบเอกสาร</strong>
                                    <p class="text-muted small">เจ้าหน้าที่ตรวจสอบและติดต่อกลับเพื่อขอเอกสาร</p>
                                </div>
                            </div>
                            <div class="step d-flex align-items-start">
                                <div class="step-number me-3">3</div>
                                <div>
                                    <strong>ชำระเงิน</strong>
                                    <p class="text-muted small">ชำระเงินตามเงื่อนไขที่เลือกไว้</p>
                                </div>
                            </div>
                            <div class="step d-flex align-items-start">
                                <div class="step-number me-3">4</div>
                                <div>
                                    <strong>โอนกรรมสิทธิ์</strong>
                                    <p class="text-muted small">ดำเนินการโอนกรรมสิทธิ์ ณ สำนักงานที่ดิน</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle 'selected' class on condo card (only one card, so not needed here)
    </script>
</body>
</html>
