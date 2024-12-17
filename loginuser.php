<?php
session_start();
include('./config.php');

class LoginHandler
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function login($username, $password)
    {
        $errors = [];

        // Kiểm tra nhập liệu
        if (empty($username)) {
            $errors['rongten'] = 'Vui lòng nhập tài khoản';
        }
        if (empty($password)) {
            $errors['rongpass'] = 'Vui lòng nhập mật khẩu';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Kiểm tra tài khoản trong database
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $errors['khongtontai'] = 'Sai tài khoản hoặc mật khẩu';
            return ['success' => false, 'errors' => $errors];
        }

        $row = $result->fetch_assoc();

        // Kiểm tra mật khẩu (sử dụng password_verify)
        if (!password_verify($password, $row['password'])) {
            $errors['khongtontai'] = 'Sai tài khoản hoặc mật khẩu';
            return ['success' => false, 'errors' => $errors];
        }

        // Kiểm tra trạng thái tài khoản
        if ($row['status'] == 1) {
            $errors['bikhoa'] = 'Tài khoản đã bị khóa';
            return ['success' => false, 'errors' => $errors];
        }

        // Đăng nhập thành công
        return [
            'success' => true,
            'user' => [
                'id' => $row['id'],
                'username' => $row['username']
            ]
        ];
    }
}

$errors = [];
$name = '';
$pass = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btndangnhap'])) {
    $name = $_POST['logname'] ?? '';
    $pass = $_POST['logpass'] ?? '';

    $loginHandler = new LoginHandler($conn);
    $result = $loginHandler->login($name, $pass);

    if ($result['success']) {
        $_SESSION['login'] = 1;
        $_SESSION['id_user'] = $result['user']['id'];
        $_SESSION['username'] = $result['user']['username'];
        header('Location: index.php');
        exit();
    } else {
        $errors = $result['errors'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link rel="stylesheet" href="./loginsignupcss.css">
    <title>Đăng nhập</title>
</head>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="loginuser.php" class="sign-in-form" id="dangnhapform" method="post">
                    <div class="dangnhap-title">
                        <h2 class="dangnhap-titlee title">Đăng nhập</h2>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="Tên tài khoản" name="logname" value="<?= htmlspecialchars($name); ?>" />
                        <?php if (isset($errors['rongten'])): ?>
                            <small><?= htmlspecialchars($errors['rongten']); ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Mật khẩu" name="logpass" value="<?= htmlspecialchars($pass); ?>" />
                        <?php if (isset($errors['rongpass'])): ?>
                            <small><?= htmlspecialchars($errors['rongpass']); ?></small>
                        <?php elseif (isset($errors['khongtontai'])): ?>
                            <small><?= htmlspecialchars($errors['khongtontai']); ?></small>
                        <?php elseif (isset($errors['bikhoa'])): ?>
                            <small><?= htmlspecialchars($errors['bikhoa']); ?></small>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn solid" name="btndangnhap">Đăng nhập</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>