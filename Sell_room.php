<?php
session_start();
include 'ConnectDB.php';

if (!isset($_SESSION['User_id'])) {
    die("กรุณาเข้าสู่ระบบก่อนขายห้อง <a href='login.php'>เข้าสู่ระบบ</a>");
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="shortcut icon" href="img/condo.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ขายคอนโดของคุณ - Chonburi Condo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f8f9fa; }
        .card { border: none; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1); }
        .btn-group-toggle .btn.active { background-color: #343a40; color: white; border-color: #343a40; }
        .btn-group-toggle .btn:not(.active) { background-color: #e9ecef; color: #495057; border-color: #ced4da; }
        .file-upload-wrapper { border: 2px dashed #ced4da; border-radius: .375rem; padding: 2rem; text-align: center; cursor: pointer; background-color: #fff; }
        .file-upload-wrapper:hover { background-color: #f8f9fa; }
        .file-upload-wrapper input[type="file"] { display: none; }
        .form-label { font-weight: 500; }
        .btn-dark-custom { background-color: #343a40; color: white; border: none; }
        .btn-dark-custom:hover { background-color: #23272b; color: white; }
        .btn-light-custom { background-color: #e9ecef; border-color: #ced4da; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Chonburi Condo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">ซื้อคอนโด</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">ขายคอนโด</a></li>
<!--                     <li class="nav-item"><a class="nav-link" href="#">เกี่ยวกับเรา</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">ติดต่อ</a></li> -->
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold">ขายคอนโดของคุณ</h1>
            <p class="text-muted">ลงประกาศขายคอนโดง่ายๆ ภายใน 5 นาที พร้อมเข้าถึงผู้ซื้อคุณภาพ</p>
        </div>

        <div class="card p-4 p-md-5">
            <form method="post" action="Sell_Room_action.php" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h5 class="mb-3 fw-bold">ข้อมูลพื้นฐาน</h5>
                        <div class="mb-3">
                            <label for="Room_number" class="form-label">ชื่อห้อง / โครงการ *</label>
                            <input type="text" class="form-control" id="Room_number" name="Room_number" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ประเภทอสังหาริมทรัพย์</label>
                            <div class="btn-group w-100 btn-group-toggle" data-toggle="buttons">
                                <label class="btn active">
                                    <input type="radio" name="propertyType" value="condo" checked> คอนโด
                                </label>
                                <label class="btn">
                                    <input type="radio" name="propertyType" value="apartment"> อพาร์ทเมนท์
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="Room_bedroom" class="form-label">ห้องนอน</label>
                                <select class="form-select" id="Room_bedroom" name="Room_bedroom">
                                    <option value="">เลือกจำนวน</option>
                                    <option value="1">1 ห้องนอน</option>
                                    <option value="2">2 ห้องนอน</option>
                                    <option value="3">3 ห้องนอน</option>
                                    <option value="4">4+ ห้องนอน</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Room_bathroom" class="form-label">ห้องน้ำ</label>
                                <select class="form-select" id="Room_bathroom" name="Room_bathroom">
                                    <option value="">เลือกจำนวน</option>
                                    <option value="1">1 ห้องน้ำ</option>
                                    <option value="2">2 ห้องน้ำ</option>
                                    <option value="3">3+ ห้องน้ำ</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="Room_price" class="form-label">ราคา (บาท) *</label>
                                <input type="number" class="form-control" id="Room_price" name="Room_price" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Room_size" class="form-label">พื้นที่ (ตร.ม.) *</label>
                                <input type="number" class="form-control" id="Room_size" name="Room_size" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="Room_location" class="form-label">ที่อยู่/ทำเล</label>
                            <input type="text" class="form-control" id="Room_location" name="Room_location">
                        </div>
                        <div class="mb-3">
                            <label for="Room_floor" class="form-label">ชั้น</label>
                            <input type="number" class="form-control" id="Room_floor" name="Room_floor">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">สภาพเฟอร์นิเจอร์</label>
                            <div class="btn-group w-100 btn-group-toggle" data-toggle="buttons">
                                <label class="btn">
                                    <input type="radio" name="furniture" value="none"> ไม่ให้เฟอร์
                                </label>
                                <label class="btn">
                                    <input type="radio" name="furniture" value="partial"> ให้เฟอร์บางส่วน
                                </label>
                                <label class="btn">
                                    <input type="radio" name="furniture" value="full"> ให้เฟอร์ครบ
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">สภาพห้อง</label>
                            <div class="btn-group w-100 btn-group-toggle" data-toggle="buttons">
                                <label class="btn">
                                    <input type="radio" name="condition" value="excellent"> ดีเยี่ยม
                                </label>
                                <label class="btn active">
                                    <input type="radio" name="condition" value="good" checked> ดี
                                </label>
                                <label class="btn">
                                    <input type="radio" name="condition" value="average"> ปานกลาง
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="mb-3 fw-bold">รายละเอียดเพิ่มเติม</h5>
                        <div class="mb-4">
                            <label for="description" class="form-label">รายละเอียด</label>
                            <textarea class="form-control" id="description" name="description" rows="6"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">รูปภาพ (สูงสุด 10 รูป)</label>
                            <div class="file-upload-wrapper" onclick="document.getElementById('fileUpload').click();">
                                <p class="mb-1">ลากและวางรูปภาพที่นี่ หรือคลิกเพื่อเลือกไฟล์</p>
                                <small class="text-muted">รองรับไฟล์ JPG, PNG ขนาดไม่เกิน 5MB ต่อรูป</small>
                                <input type="file" id="fileUpload" name="Picture" multiple accept="image/png, image/jpeg">
                            </div>
                        </div>
                        <h5 class="mb-3 fw-bold">ข้อมูลติดต่อ</h5>
                        <div class="mb-3">
                            <label for="phone" class="form-label">เบอร์โทรศัพท์ *</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" value="1" id="termsCheck" name="termsCheck" required>
                    <label class="form-check-label" for="termsCheck">
                        ยอมรับ<a href="#">เงื่อนไขการใช้งาน</a> และ <a href="#">นโยบายความเป็นส่วนตัว</a>
                    </label>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="index.php" class="btn btn-light-custom px-4">ยกเลิก</a>
                    <button type="submit" class="btn btn-dark-custom px-4">ลงประกาศ</button>
                </div>
            </form>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

