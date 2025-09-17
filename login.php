<?php
// ---- PHP ฟังก์ชัน login เดิมของคุณ ----
session_start();
include("ConnectDB.php"); // ไฟล์เชื่อมต่อ DB

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Username = mysqli_real_escape_string($conn, $_POST['username']);
    $Password = mysqli_real_escape_string($conn, $_POST['password']);

    $result = $conn->query("SELECT * FROM users WHERE Username='$Username'");
    $User = $result->fetch_assoc();

    if ($User) {
        if ($Password === $User['Password']) {
            $_SESSION['User_id'] = $User['User_id'];
            $_SESSION['Username'] = $User['Username'];
            $_SESSION['Role'] = $User['Role'];
            if ($User['Role'] === 'Admin') {
                header("Location: manage_room.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
        }
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <link rel="shortcut icon" href="img/condo.png" type="image/x-icon">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เข้าสู่ระบบ | Chonburi Condo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .login-box {
      max-width: 450px;
      margin: 60px auto;
      padding: 30px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    footer {
      background: #212529;
      color: #ddd;
      padding: 40px 0;
      margin-top: 80px;
    }
    footer a { color: #ddd; text-decoration: none; }
    footer a:hover { text-decoration: underline; }
  </style>
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Chonburi Condo</a>
    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="index.php">ซื้อคอนโด</a></li>
        <li class="nav-item"><a class="nav-link" href="Sell_room.php">ขายคอนโด</a></li>
<!--         <li class="nav-item"><a class="nav-link" href="#">เกี่ยวกับเรา</a></li>
        <li class="nav-item"><a class="nav-link" href="#">ติดต่อ</a></li> -->
      </ul>
    </div>
  </div>
</nav>

<!-- Login Form -->
<div class="container">
  <div class="text-center mt-5">
    <h2 class="fw-bold">เข้าสู่ระบบ</h2>
    <p class="text-muted">เข้าสู่ระบบเพื่อเข้าถึงบริการของเราอย่างเต็มรูปแบบ</p>
  </div>

  <div class="login-box">
    <ul class="nav nav-tabs mb-4">
      <li class="nav-item">
        <a class="nav-link active" href="#">เข้าสู่ระบบ</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="register.php">สมัครสมาชิก</a>
      </li>
    </ul>

    <?php if (isset($error)) : ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">ชื่อผู้ใช้</label>
        <input type="text" name="username" class="form-control" placeholder="กรอกชื่อผู้ใช้ของคุณ" required>
      </div>

      <div class="mb-3">
        <label class="form-label">รหัสผ่าน</label>
        <div class="input-group">
          <input type="password" name="password" id="password" class="form-control" placeholder="กรอกรหัสผ่านของคุณ" required>
          <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">👁</button>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <input type="checkbox" name="remember"> จดจำการเข้าสู่ระบบ
        </div>
        <a href="#" class="text-decoration-none">ลืมรหัสผ่าน?</a>
      </div>

      <button type="submit" class="btn btn-dark w-100">เข้าสู่ระบบ</button>

      <div class="text-center my-3">หรือ</div>

      <div class="d-flex gap-2">
        <a href="#" class="btn btn-outline-secondary w-50">Google</a>
        <a href="#" class="btn btn-outline-secondary w-50">Facebook</a>
      </div>
    </form>

    <div class="text-center mt-3">
      <small>ยังไม่มีบัญชี? <a href="register.php" class="fw-bold">สมัครสมาชิก</a></small>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-3 mb-3">
        <h6 class="fw-bold">เกี่ยวกับเรา</h6>
        <ul class="list-unstyled">
          <li><a href="#">เกี่ยวกับบริษัท</a></li>
          <li><a href="#">ทีมงาน</a></li>
          <li><a href="#">ข่าวสาร</a></li>
        </ul>
      </div>
      <div class="col-md-3 mb-3">
        <h6 class="fw-bold">บริการ</h6>
        <ul class="list-unstyled">
          <li><a href="#">ซื้อคอนโด</a></li>
          <li><a href="#">ขายคอนโด</a></li>
          <li><a href="#">ประเมินราคา</a></li>
        </ul>
      </div>
      <div class="col-md-3 mb-3">
        <h6 class="fw-bold">ช่วยเหลือ</h6>
        <ul class="list-unstyled">
          <li><a href="#">คำถามที่พบบ่อย</a></li>
          <li><a href="#">ติดต่อเรา</a></li>
          <li><a href="#">สนับสนุน</a></li>
        </ul>
      </div>
      <div class="col-md-3 mb-3">
        <h6 class="fw-bold">ติดต่อ</h6>
        <p class="mb-1">โทร: 038-123-456</p>
        <p class="mb-1">อีเมล: info@chonburicondo.com</p>
        <p>ที่อยู่: ชลบุรี</p>
      </div>
    </div>
    <div class="text-center mt-4">
      <small>© 2024 Chonburi Condo. สงวนลิขสิทธิ์.</small>
    </div>
  </div>
</footer>

<script>
function togglePassword() {
  const pwd = document.getElementById("password");
  pwd.type = pwd.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
