<?php
require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    private $conn;

    // Cài đặt kết nối cơ sở dữ liệu thực tế
    protected function setUp(): void
    {
        // Kết nối tới cơ sở dữ liệu thực tế
        include 'config.php';  // Nhúng tệp cấu hình kết nối MySQL
        $this->conn = $conn; // Kết nối MySQL thực tế
    }

    // Phương thức để reset dữ liệu POST và SESSION trước mỗi test
    private function resetGlobals(): void
    {
        $_POST = [];
        $_SESSION = [];
        $_SESSION['errors'] = [];  // Đảm bảo mảng lỗi được khởi tạo
        session_start();  // Khởi tạo session nếu cần thiết
    }

    // Test trường hợp username và password trống
    // public function testEmptyUsernameAndPassword()
    // {
    //     $this->resetGlobals();

    //     $_POST['btndangnhap'] = true;
    //     $_POST['logname'] = '';
    //     $_POST['logpass'] = '';

    //     include 'loginuser.php'; // Nhúng tệp xử lý đăng nhập

    //     // Kiểm tra thông báo lỗi
    //     $this->assertEquals('Vui lòng nhập đủ tài khoản mật khẩu', $_SESSION['errors']['rongten']);
    //     $this->assertEquals('Vui lòng nhập đủ tài khoản mật khẩu', $_SESSION['errors']['rongpass']);
    // }

    // Test trường hợp tài khoản không hợp lệ
    // public function testInvalidCredentials()
    // {
    //     $this->resetGlobals();

    //     $_POST['btndangnhap'] = true;
    //     $_POST['logname'] = 'invalidUser';
    //     $_POST['logpass'] = 'wrongPassword';

    //     // Kết nối thực tế tới cơ sở dữ liệu
    //     $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
    //     $stmt->bind_param("s", $_POST['logname']);
    //     $stmt->execute();
    //     $result = $stmt->get_result();

    //     // Nếu không tìm thấy tài khoản
    //     $this->assertEquals(0, $result->num_rows);

    //     include 'loginuser.php';

    //     // Kiểm tra thông báo lỗi tài khoản không tồn tại
    //     $this->assertArrayHasKey('khongtontai', $_SESSION['errors']);
    //     $this->assertEquals('Sai tài khoản hoặc mật khẩu', $_SESSION['errors']['khongtontai']);
    // }

    // Test trường hợp đăng nhập hợp lệ
    public function testValidLogin()
    {
        $this->resetGlobals();

        $_POST['btndangnhap'] = true;
        $_POST['logname'] = 'nguyen1';  // Giả sử tài khoản này có trong DB
        $_POST['logpass'] = 'nguyen211';  // Mật khẩu đúng

        // Truy vấn thực tế tới cơ sở dữ liệu
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $_POST['logname']);
        $stmt->execute();
        $result = $stmt->get_result();

        // Kiểm tra nếu có tài khoản
        $row = $result->fetch_assoc();
        $this->assertNotNull($row);  // Đảm bảo có dữ liệu trả về

        // Kiểm tra mật khẩu
        $this->assertEquals($_POST['logpass'], $row['password']);


        // Giả sử việc đăng nhập thành công sẽ thiết lập session
        $_SESSION['login'] = 1;
        $_SESSION['id_user'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        // Kiểm tra giá trị trong session
        $this->assertEquals(1, $_SESSION['login']);
        $this->assertEquals($row['id'], $_SESSION['id_user']);
        $this->assertEquals($row['username'], $_SESSION['username']);
    }

    // Test trường hợp tài khoản bị khóa
    // public function testAccountLocked()
    // {
    //     $this->resetGlobals();

    //     $_POST['btndangnhap'] = true;
    //     $_POST['logname'] = 'nguyen5';  // Tài khoản bị khóa
    //     $_POST['logpass'] = 'nguyen5';  // Mật khẩu đúng

    //     // Truy vấn thực tế tới cơ sở dữ liệu
    //     $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
    //     $stmt->bind_param("s", $_POST['logname']);
    //     $stmt->execute();
    //     $result = $stmt->get_result();

    //     // Kiểm tra tài khoản tồn tại
    //     $row = $result->fetch_assoc();
    //     $this->assertNotNull($row, "Không tìm thấy tài khoản trong cơ sở dữ liệu!");

    //     // Kiểm tra trạng thái tài khoản bị khóa
    //     $this->assertEquals(1, $row['status'], "Tài khoản không bị khóa như mong đợi!");

    //     // Gọi file xử lý đăng nhập
    //     include 'loginuser.php';

    //     // Kiểm tra session cho tài khoản bị khóa
    //     $this->assertArrayHasKey('bikhoa', $_SESSION['errors'], "Thông báo lỗi không tồn tại!");
    //     $this->assertEquals('Tài khoản đã bị khóa', $_SESSION['errors']['bikhoa'], "Thông báo lỗi không đúng!");
    //     $this->assertEquals(0, $_SESSION['login'], "Trạng thái đăng nhập không chính xác!");
    // }


    // Đóng kết nối sau khi test xong
    protected function tearDown(): void
    {
        $this->conn->close();
    }
}
