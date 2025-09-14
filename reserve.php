<?php
session_start();
include 'ConnectDB.php';

// Logic เดิมของคุณยังคงอยู่ครบถ้วน
// Accept Room_id from GET or POST
$Room_id = $_GET['Room_id'] ?? $_POST['Room_id'] ?? null;

if (!isset($_SESSION['User_id'])) {
    // อาจจะ Redirect ไปหน้า login แทนการ die() เพื่อประสบการณ์ที่ดีกว่า
    header('Location: login.php?redirect_to=reserve.php?Room_id=' . $Room_id);
    exit;
}

if (!$Room_id) {
    die("ไม่พบรหัสห้อง");
}

// ใช้ Prepared Statements เพื่อความปลอดภัย
$stmt = $conn->prepare("SELECT * FROM room_db WHERE Room_id = ?");
$stmt->bind_param("i", $Room_id);
$stmt->execute();
$result = $stmt->get_result();
$room = $result->fetch_assoc();

// Adjust status check to match your DB values (Empty, Sold, etc.)
if (!$room || $room['Status'] != 'Empty') {
    die("ไม่สามารถจองห้องนี้ได้ในขณะนี้");
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าห้องคอนโด - Chonburi Condo</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 0.75rem;
            border: 1px solid #e0e0e0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #e0e0e0;
            font-weight: 500;
        }
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .steps .step-number {
            background-color: #0d6efd;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
        }
        .contact-info li {
            list-style: none;
            padding-left: 0;
            margin-bottom: 10px;
        }
        .contact-info i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="text-center mb-5">
            <h1>เข้าห้องคอนโด</h1>
            <p class="text-muted">กรอกข้อมูลให้ครบถ้วนเพื่อเข้าสู่ขั้นตอนการจอง</p>
        </div>

        <form method="post" action="reserve_action.php">
            <input type="hidden" name="Room_id" value="<?= htmlspecialchars($room['Room_id']); ?>">
            <input type="hidden" name="Room_number" value="<?= htmlspecialchars($room['Room_number']); ?>">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>เลือกคอนโด / ห้อง</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/400x240" alt="Room Image" class="img-fluid rounded" style="width: 150px; height: 90px; object-fit: cover;">
                                <div class="ms-3">
                                    <h6 class="mb-1"><?= htmlspecialchars($room['Room_number'] ?? 'ชื่อห้องตัวอย่าง'); ?></h6>
                                    <h5 class="text-primary fw-bold mb-1">฿<?= number_format($room['Room_price']); ?></h5>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($room['Bedrooms'] ?? '1'); ?> ห้องนอน &middot; 
                                        <?= htmlspecialchars($room['Bathrooms'] ?? '1'); ?> ห้องน้ำ &middot; 
                                        <?= htmlspecialchars($room['Room_size']); ?> ตร.ม.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>ข้อมูลส่วนตัว</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="firstname" class="form-label">ชื่อ *</label>
                                    <input type="text" class="form-control" id="firstname" name="Fullname" placeholder="กรอกชื่อ" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">เบอร์โทรศัพท์ *</label>
                                    <input type="tel" class="form-control" id="Phone" name="Phone" placeholder="กรอกเบอร์โทรศัพท์" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">อีเมล *</label>
                                    <input type="email" class="form-control" id="email" name="Email" placeholder="กรอกอีเมล" required>
                                </div>
                                <div class="col-12">
                                    <label for="id_card" class="form-label">เลขบัตรประชาชน *</label>
                                    <input type="text" class="form-control" id="id_card" name="IdCard" placeholder="กรอกหมายเลขบัตรประชาชน 13 หลัก" maxlength="13" required>
                                </div>
                                <div class="col-12">
                                    <label for="address" class="form-label">ที่อยู่ *</label>
                                    <textarea class="form-control" id="address" name="Address" rows="3" placeholder="กรอกที่อยู่ปัจจุบัน" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>วิธีการชำระเงิน</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="bankTransfer" value="bank_transfer" checked>
                                <label class="form-check-label" for="bankTransfer">
                                    โอนเงินผ่านธนาคาร
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="installments" value="installments">
                                <label class="form-check-label" for="installments">
                                    ผ่อนชำระ
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="internetBanking" value="internet_banking">
                                <label class="form-check-label" for="internetBanking">
                                    อินเทอร์เน็ตแบงค์กิ้ง
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="loan" value="loan">
                                <label class="form-check-label" for="loan">
                                    ติดต่อธนาคารเพื่อเดินสินเชื่อ
                                </label>
                            </div>
                        </div>
                    </div>
                     <button type="submit" class="btn btn-primary w-100 btn-lg">ยืนยันการจองและชำระเงิน</button>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>สรุปการเช่า</h5>
                        </div>
                        <div class="card-body">
                             <div class="d-flex justify-content-between">
                                <span><?= htmlspecialchars($room['Room_number'] ?? 'ชื่อห้องตัวอย่าง'); ?></span>
                                <strong>฿<?= number_format($room['Room_price']); ?></strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="h5">ยอดรวม</span>
                                <span class="h5 text-primary fw-bold">฿<?= number_format($room['Room_price']); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>ข้อมูลการติดต่อ</h5>
                        </div>
                        <div class="card-body">
                           <ul class="p-0 contact-info">
                               <li><i class="fas fa-phone"></i> 038-123-456</li>
                               <li><i class="fas fa-envelope"></i> info@chonburicondo.com</li>
                               <li><i class="fas fa-map-marker-alt"></i> ชลบุรี ประเทศไทย</li>
                           </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5>ขั้นตอนการเช่า</h5>
                        </div>
                        <div class="card-body steps">
                           <ul class="list-unstyled">
                               <li class="d-flex align-items-start mb-3">
                                   <div class="step-number">1</div>
                                   <div>
                                       <strong>กรอกข้อมูลและเลือกเอกสารการชำระ</strong>
                                       <p class="small text-muted mb-0">กรอกข้อมูลส่วนตัวและเลือกช่องทางการชำระเงิน</p>
                                   </div>
                               </li>
                               <li class="d-flex align-items-start mb-3">
                                   <div class="step-number">2</div>
                                   <div>
                                       <strong>ตรวจสอบเอกสาร</strong>
                                       <p class="small text-muted mb-0">เจ้าหน้าที่จะติดต่อกลับเพื่อตรวจสอบเอกสาร</p>
                                   </div>
                               </li>
                               <li class="d-flex align-items-start">
                                   <div class="step-number">3</div>
                                   <div>
                                       <strong>ชำระเงิน</strong>
                                       <p class="small text-muted mb-0">ชำระเงินผ่านช่องทางที่เลือกไว้</p>
                                   </div>
                               </li>
                           </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="detail.php?Room_id=<?= $room['Room_id']; ?>">← กลับไปหน้ารายละเอียดห้อง</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>