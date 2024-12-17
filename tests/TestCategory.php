<?php

use PHPUnit\Framework\TestCase;

class TestCategory extends TestCase
{
    private $conn;

    // Thiết lập kết nối CSDL trước khi chạy test
    protected function setUp(): void
    {
        // Kết nối tới CSDL
        include 'config.php';
        $this->conn = $conn;

        // Kiểm tra kết nối
        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }

        // Xóa dữ liệu cũ nếu có
        $this->conn->query("DELETE FROM categories WHERE name = 'Danh mục mới' OR name = 'Danh mục đã sửa' OR name = 'Danh mục xóa' OR name = 'Danh mục ẩn'");

        // Tạo một số danh mục mẫu cho các test
        $this->conn->query("INSERT INTO categories (name) VALUES ('Danh mục mẫu')");
    }

    // Test thêm danh mục
    public function testAddCategory()
    {
        // Giả lập dữ liệu POST
        $_POST['nutthemdanhmuc'] = true;
        $_POST['danhmuc'] = 'Danh mục mới';

        // Gọi file thêm danh mục
        ob_start();
        include './adminadddanhmuc.php';
        ob_end_clean();

        // Kiểm tra sản phẩm đã được thêm
        $result = $this->conn->query("SELECT * FROM categories WHERE name = 'Danh mục mới'");
        $category = $result->fetch_assoc();

        $this->assertNotNull($category);
        $this->assertEquals('Danh mục mới', $category['name']);
    }

    // Test sửa danh mục
    public function testEditCategory()
    {
        // Thêm danh mục mẫu để sửa
        $this->conn->query("INSERT INTO categories (name) VALUES ('Danh mục cũ')");

        // Lấy ID của danh mục vừa thêm
        $lastId = $this->conn->insert_id;

        // Giả lập dữ liệu POST
        $_POST['nutsuadanhmuc'] = true;
        $_POST['danhmuc'] = 'Danh mục đã sửa';

        // Giả lập thay đổi danh mục với ID vừa lấy
        $_GET['id_dm'] = $lastId;

        // Gọi file sửa danh mục
        ob_start();
        include './adminsuadanhmuc.php';
        ob_end_clean();

        // Kiểm tra danh mục đã được sửa
        $result = $this->conn->query("SELECT * FROM categories WHERE id = $lastId");
        $category = $result->fetch_assoc();

        $this->assertEquals('Danh mục đã sửa', $category['name']);
    }

    // Test xóa danh mục
    public function testDeleteCategory()
    {
        // Thêm danh mục mẫu để xóa
        $this->conn->query("INSERT INTO categories (name) VALUES ('Danh mục xóa')");

        // Lấy ID của danh mục vừa thêm
        $lastId = $this->conn->insert_id;

        // Giả lập xóa danh mục
        $_GET['id_dm'] = $lastId;

        ob_start();
        include './adminxoadanhmuc.php';
        ob_end_clean();

        // Kiểm tra danh mục đã bị xóa
        $result = $this->conn->query("SELECT * FROM categories WHERE id = $lastId");
        $this->assertEquals(0, $result->num_rows, "Danh mục không được xóa thành công.");
    }

    // Test ẩn danh mục
    public function testHideCategory()
    {
        // Thêm danh mục mẫu để ẩn
        $this->conn->query("INSERT INTO categories (name) VALUES ('Danh mục ẩn')");

        // Lấy ID của danh mục vừa thêm
        $lastId = $this->conn->insert_id;

        // Giả lập ẩn danh mục
        $_GET['id_dm'] = $lastId;

        ob_start();
        include './adminhandanhmuc.php';
        ob_end_clean();

        // Kiểm tra trạng thái danh mục đã được ẩn
        $result = $this->conn->query("SELECT * FROM categories WHERE id = $lastId");
        $category = $result->fetch_assoc();

        // Kiểm tra xem trạng thái có phải là 0 (đã ẩn) không
        $this->assertEquals(0, $category['status'], "Danh mục không được ẩn thành công.");
    }

    // Dọn dẹp dữ liệu sau khi test
    protected function tearDown(): void
    {
        // Xóa danh mục đã thêm trong quá trình test
        $this->conn->query("DELETE FROM categories WHERE name = 'Danh mục mới' OR name = 'Danh mục đã sửa' OR name = 'Danh mục xóa' OR name = 'Danh mục ẩn'");

        $this->conn->close();
    }
}
