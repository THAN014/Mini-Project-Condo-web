<?php
include 'ConnectDB.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];
    $Phone = $_POST['Phone'];
    $Email = $_POST['Email'];
    $sql = "INSERT INTO users (Username, Password , Phone , Email) VALUES ('$Username', '$Password' , '$Phone' , '$Email')";
    if ($conn->query($sql)) {
        header("Location: login.php");
        exit;
    } else {
        $error = "เกิดข้อผิดพลาด: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - Chonburi Condo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, .075);
        }

        .form-container {
            max-width: 700px;
            margin: auto;
        }

        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .1);
        }

        .benefits-section .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #343a40;
            border: 1px solid #dee2e6;
        }

        footer {
            background-color: #343a40;
            color: #adb5bd;
        }

        footer a {
            color: #dee2e6;
            text-decoration: none;
        }

        footer a:hover {
            color: #fff;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Chonburi Condo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">ซื้อคอนโด</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">ขายคอนโด</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">เกี่ยวกับเรา</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">ติดต่อ</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="form-container">
            <div class="text-center mb-4">
                <h1 class="fw-bold">สมัครสมาชิก</h1>
                <p class="text-muted">เข้าร่วมกับเราเพื่อรับข้อมูลคอนโดล่าสุดและข้อเสนอพิเศษ</p>
            </div>

            <div class="card p-4 p-md-5">
                <form method="post" action="">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="firstName" class="form-label">Username *</label>
                            <input type="text" class="form-control" id="firstName" placeholder="กรอกชื่อของคุณ" name="Username" required>
                        </div>
                        <div class="col-12">
                            <label for="email" class="form-label">อีเมล *</label>
                            <input type="email" class="form-control" id="Email" placeholder="example@email.com" name="Email" required>
                        </div>
                        <div class="col-12">
                            <label for="phone" class="form-label">เบอร์โทรศัพท์ *</label>
                            <input type="tel" class="form-control" id="Phone" placeholder="08X-XXX-XXXX" name="Phone" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">รหัสผ่าน *</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="Password" placeholder="อย่างน้อย 8 ตัวอักษร" name="Password" required>
                                <span class="input-group-text bg-transparent"><i class="bi bi-eye"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="confirmPassword" class="form-label">ยืนยันรหัสผ่าน *</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirmPassword" placeholder="ยืนยันรหัสผ่าน">
                                <span class="input-group-text bg-transparent"><i class="bi bi-eye"></i></span>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">ความสนใจ (เลือกได้หลายข้อ)</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="interest1" value="buy">
                                    <label class="form-check-label" for="interest1">ซื้อคอนโด</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="interest2" value="sell">
                                    <label class="form-check-label" for="interest2">ขายคอนโด</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="interest3" value="rent">
                                    <label class="form-check-label" for="interest3">เช่าคอนโด</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="interest4" value="invest">
                                    <label class="form-check-label" for="interest4">ลงทุน</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms">
                                <label class="form-check-label" for="terms">
                                    ฉันยอมรับ <a href="#">เงื่อนไขการใช้งาน</a> และ <a href="#">นโยบายความเป็นส่วนตัว</a> *
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-dark w-100 py-2">สมัครสมาชิก</button>
                        </div>
                    </div>
                </form>
            </div>
            <p class="text-center mt-3">มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
        </div>
    </main>

    <section class="container text-center py-5">
        <h2 class="fw-bold mb-3">ประโยชน์ของการเป็นสมาชิก</h2>
        <p class="text-muted mb-5">รับสิทธิพิเศษและบริการดีๆจากเรา</p>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="icon-circle mx-auto mb-3">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <h5 class="fw-bold">ข้อมูลล่าสุด</h5>
                <p class="text-muted">รับข้อมูลคอนโดใหม่และข้อเสนอพิเศษก่อนใครทุกสัปดาห์</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="icon-circle mx-auto mb-3">
                    <i class="bi bi-headset"></i>
                </div>
                <h5 class="fw-bold">ปรึกษาฟรี</h5>
                <p class="text-muted">รับคำปรึกษาจากผู้เชี่ยวชาญด้านอสังหาริมทรัพย์ฟรี 24/7</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="icon-circle mx-auto mb-3">
                    <i class="bi bi-gem"></i>
                </div>
                <h5 class="fw-bold">ส่วนลดพิเศษ</h5>
                <p class="text-muted">รับส่วนลดและข้อเสนอพิเศษเฉพาะสมาชิกเท่านั้น</p>
            </div>
        </div>
    </section>

    <footer class="pt-5 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <h5 class="text-white">เกี่ยวกับเรา</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">เกี่ยวกับบริษัท</a></li>
                        <li><a href="#">ทีมงาน</a></li>
                        <li><a href="#">ข่าวสาร</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3">
                    <h5 class="text-white">บริการ</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">ซื้อคอนโด</a></li>
                        <li><a href="#">ขายคอนโด</a></li>
                        <li><a href="#">ประเมินราคา</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3">
                    <h5 class="text-white">ช่วยเหลือ</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">คำถามที่พบบ่อย</a></li>
                        <li><a href="#">ติดต่อเรา</a></li>
                        <li><a href="#">สนับสนุน</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3">
                    <h5 class="text-white">ติดต่อ</h5>
                    <p>โทร: 038-123-456<br>
                        อีเมล: info@chonburicondo.com<br>
                        ที่อยู่: ชลบุรี</p>
                </div>
            </div>
            <hr class="text-secondary">
            <p class="text-center text-secondary small">&copy; 2024 Chonburi Condo. สงวนลิขสิทธิ์.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>