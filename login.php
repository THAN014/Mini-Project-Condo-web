<?php
session_start();
include 'ConnectDB.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Username = $_POST['username'];
    $Password = $_POST['password'];

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
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>เข้าสู่ระบบ - Chonburi Condo</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="globals.css" />
</head>

<body>
    <header>
        <div class="container header-content">
            <div class="logo">Chonburi Condo</div>
            <nav>
                <ul>
                    <li><a href="#">ซื้อคอนโด</a></li>
                    <li><a href="#">ขายคอนโด</a></li>
                    <li><a href="#">เกี่ยวกับเรา</a></li>
                    <li><a href="#">ติดต่อ</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="login-section">
            <div class="container">
                <h1>เข้าสู่ระบบ</h1>
                <p>เข้าสู่ระบบเพื่อเข้าใช้บริการของเราอย่างเต็มรูปแบบ</p>

                <div class="login-tabs">
                    <button class="tab-button active" id="loginTab">เข้าสู่ระบบ</button>
                    <a href="register.php" class="tab-button">สมัครสมาชิก</a>
                </div>

                <div class="login-form-container">
                    <form class="login-form" id="loginForm" method="post" action="">

                        <label for="username">ชื่อผู้ใช้</label>
                        <div class="username-input-wrapper">
                            <input type="text" class="form-control" id="username" placeholder="กรอก Username ของคุณ" name="username" required>

                        </div>
                        <label for="password">รหัสผ่าน</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="password" name="password" placeholder="กรอกรหัสผ่านของคุณ" required>
                            <span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fas fa-eye"></i></span>
                        </div>

                        <div class="remember-me-forgot-password">
                            <label>
                                <input type="checkbox" id="rememberMe"> จดจำการเข้าสู่ระบบ
                            </label>
                            <a href="#">ลืมรหัสผ่าน?</a>
                        </div>

                        <button type="submit" class="primary-button">เข้าสู่ระบบ</button>
                        <?php if ($error): ?>
                            <div class="text-danger mt-3" style="font-weight:bold;">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <div class="or-divider">หรือ</div>

                        <div class="social-login">
                            <button type="button" class="social-button google"><i class="fab fa-google"></i> Google</button>
                            <button type="button" class="social-button facebook"><i class="fab fa-facebook-f"></i> Facebook</button>
                        </div>

                        <p class="no-account">ยังไม่มีบัญชีอยู่แล้ว? <a href="register.php">สมัครสมาชิก</a></p>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container footer-grid">
            <div class="footer-col">
                <h3>เกี่ยวกับเรา</h3>
                <ul>
                    <li><a href="#">ทีมงาน</a></li>
                    <li><a href="#">ข่าวสาร</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>บริการ</h3>
                <ul>
                    <li><a href="#">ซื้อคอนโด</a></li>
                    <li><a href="#">ขายคอนโด</a></li>
                    <li><a href="#">ประเมินราคา</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>ช่วยเหลือ</h3>
                <ul>
                    <li><a href="#">คำถามที่พบบ่อย</a></li>
                    <li><a href="#">ติดต่อเรา</a></li>
                    <li><a href="#">สนับสนุน</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>ติดต่อ</h3>
                <p>โทร: 038-123-456</p>
                <p>อีเมล: info@chonburicondo.com</p>
                <p>ที่อยู่: ชลบุรี</p>
            </div>
        </div>
        <div class="container copyright">
            <p>&copy; 2024 Chonburi Condo. สงวนลิขสิทธิ์.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>