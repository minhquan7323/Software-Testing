<?php
session_start();
include('./config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btndangnhap'])) {

    // Khởi tạo lỗi nếu chưa có
    if (!isset($_SESSION['errors'])) {
        $_SESSION['errors'] = [];
    }

    $_SESSION['login'] = 0; // Mặc định là chưa đăng nhập
    $name = mysqli_real_escape_string($conn, $_POST['logname']); // Làm sạch dữ liệu đầu vào
    $pass = $_POST['logpass'];

    // Kiểm tra trường nhập liệu
    if (empty($name)) {
        $_SESSION['errors']['rongten'] = 'Vui lòng nhập đủ tài khoản mật khẩu';
    }
    if (empty($pass)) {
        $_SESSION['errors']['rongpass'] = 'Vui lòng nhập đủ tài khoản mật khẩu';
    }

    // Kiểm tra nếu không có lỗi
    if (empty($_SESSION['errors'])) {
        // Sử dụng prepared statement để tránh SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        // Kiểm tra tài khoản
        if ($result->num_rows == 0) {
            $_SESSION['errors']['khongtontai'] = 'Sai tài khoản hoặc mật khẩu';
        } else {
            $row = $result->fetch_assoc();

            // Kiểm tra mật khẩu
            if ($pass === $row['password']) {
                if ($row['status'] == 1) { // Tài khoản bị khóa
                    $_SESSION['errors']['bikhoa'] = "Tài khoản đã bị khóa";
                    $_SESSION['login'] = 0;
                } else {
                    $_SESSION['id_user'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['login'] = 1; // Đăng nhập thành công
                    $_SESSION['totalAll'] = 0; // Thêm một biến cho trang chủ (nếu cần thiết)
                    header('location: index.php'); // Chuyển hướng về trang chính
                    exit();
                }
            } else {
                $_SESSION['errors']['khongtontai'] = 'Sai tài khoản hoặc mật khẩu';
            }
        }
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
    <title>Đăng nhập / Đăng ký</title>
</head>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="loginuser.php" class="sign-in-form" id="dangnhapform" method="post">
                    <div class="dangnhap-tittle">
                        <h2 id="title" class="dangnhap-titlee title">Đăng nhập</h2>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" placeholder="Tên tài khoản" name="logname" value="<?php if (isset($name)) echo $name; ?>" />
                        <?php
                        // Hiển thị lỗi nếu có
                        if (isset($_SESSION['errors']['rongten'])) {
                            echo '<small>' . $_SESSION['errors']['rongten'] . '</small>';
                        } else if (isset($_SESSION['errors']['khongtontai'])) {
                            echo '<small>' . $_SESSION['errors']['khongtontai'] . '</small>';
                        } else if (isset($_SESSION['errors']['bikhoa'])) {
                            echo '<small>' . $_SESSION['errors']['bikhoa'] . '</small>';
                        }
                        ?>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" placeholder="Mật khẩu" name="logpass" value="<?php if (isset($pass)) echo $pass; ?>" />
                        <?php
                        if (isset($_SESSION['errors']['rongpass'])) {
                            echo '<small>' . $_SESSION['errors']['rongpass'] . '</small>';
                        } else if (isset($_SESSION['errors']['khongtontai'])) {
                            echo '<small>' . $_SESSION['errors']['khongtontai'] . '</small>';
                        }
                        ?>
                    </div>
                    <button type="submit" class="btn solid" name="btndangnhap">Đăng nhập</button>
                </form>
            </div>
        </div>
        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>Bạn là thành viên mới?</h3>
                    <p>Hãy nhấn vào nút bên dưới để đăng ký</p>
                    <button class="btn transparent" id="sign-up-btn" onclick="window.location.href= 'signupuser.php'">
                        Đăng ký
                    </button>
                </div>
                <img src="" class="image" alt="" />
            </div>
        </div>
    </div>
</body>

</html>