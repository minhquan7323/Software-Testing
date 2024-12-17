<?php

use PHPUnit\Framework\TestCase;

class TestProduct extends TestCase
{
    private $conn;

    // Thiết lập kết nối CSDL trước khi chạy test
    protected function setUp(): void
    {
        include 'config.php';
        $this->conn = $conn;

        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }

        // Xóa dữ liệu cũ (phòng tránh trùng lặp)
        $this->conn->query("DELETE FROM products WHERE name = 'Sản phẩm mới' OR name = 'Sản phẩm đã sửa' OR name = 'Sản phẩm xóa'");
        $this->conn->query("DELETE FROM categories WHERE id = 999");

        // Tạo danh mục mới
        $this->conn->query("INSERT INTO categories (id, name) VALUES (999, 'Test Category')");
    }

    // Test thêm sản phẩm
    public function testAddProduct()
    {
        // Giả lập dữ liệu POST
        $_POST['nutthemsp'] = true;
        $_POST['ten'] = 'Sản phẩm mới';
        $_POST['gia'] = 1500;
        $_POST['soluong'] = 5;
        $_POST['danh_muc'] = 999;
        $_POST['noi_dung'] = 'Mô tả sản phẩm mới';

        // Giả lập upload file
        $_FILES['hinh_anh'] = [
            'name' => 'test_image.jpg',
            'tmp_name' => __DIR__ . '/test_image.jpg',
            'error' => 0,
            'size' => 500
        ];

        // Tạo file giả lập hình ảnh nếu chưa tồn tại
        if (!file_exists(__DIR__ . '/test_image.jpg')) {
            file_put_contents(__DIR__ . '/test_image.jpg', 'dummy image content');
        }

        // Gọi file thêm sản phẩm
        ob_start();
        include './adminaddsanpham.php';
        ob_end_clean();

        // Kiểm tra kết quả
        $result = $this->conn->query("SELECT * FROM products WHERE name = 'Sản phẩm mới'");
        $product = $result->fetch_assoc();

        $this->assertNotNull($product);
        $this->assertEquals(1500, $product['price']);
        $this->assertEquals(5, $product['quantity']);
        $this->assertEquals('test_image.jpg', $product['image_url']);
        $this->assertEquals('Mô tả sản phẩm mới', $product['description']);
    }

    // Test sửa sản phẩm
    public function testEditProduct()
    {
        // Thêm sản phẩm để sửa
        $this->conn->query("INSERT INTO products (id, name, price, quantity, category_id, image_url, description) 
            VALUES (999, 'Sản phẩm cũ', 1000, 10, 999, 'old_image.jpg', 'Mô tả cũ')");

        // Giả lập dữ liệu POST
        $_POST['nutsuasp'] = true;
        $_POST['ten'] = 'Sản phẩm đã sửa';
        $_POST['gia'] = 2000;
        $_POST['soluong'] = 20;
        $_POST['danh_muc'] = 999;
        $_POST['noi_dung'] = 'Mô tả mới';

        // Giả lập upload file
        $_FILES['hinh_anh'] = [
            'name' => 'updated_image.jpg',
            'tmp_name' => __DIR__ . '/updated_image.jpg',
            'error' => 0,
            'size' => 500
        ];

        // Tạo file hình ảnh mới nếu chưa tồn tại
        if (!file_exists(__DIR__ . '/updated_image.jpg')) {
            file_put_contents(__DIR__ . '/updated_image.jpg', 'dummy image content');
        }

        // Gọi file sửa sản phẩm
        ob_start();
        $_GET['id_sp'] = 999;
        include './adminsuasanpham.php';
        ob_end_clean();

        // Kiểm tra kết quả
        $result = $this->conn->query("SELECT * FROM products WHERE id = 999");
        $product = $result->fetch_assoc();

        $this->assertEquals('Sản phẩm đã sửa', $product['name']);
        $this->assertEquals(2000, $product['price']);
        $this->assertEquals(20, $product['quantity']);
        $this->assertEquals('updated_image.jpg', $product['image_url']);
        $this->assertEquals('Mô tả mới', $product['description']);
    }

    // Test xóa sản phẩm khi chưa có đơn hàng
    public function testDeleteProductWithoutOrder()
    {
        // Thêm dữ liệu test vào bảng products
        $this->conn->query("INSERT INTO products (id, name, price, quantity, category_id, image_url, description, isshow) 
        VALUES (999, 'Sản phẩm xóa', 1000, 10, 999, 'delete_image.jpg', 'Mô tả xóa', 0)");

        // Giả lập xóa sản phẩm chưa có đơn hàng
        $_GET['id_sp'] = 999;

        ob_start();
        include './adminxoasanpham.php'; // Gọi file xóa sản phẩm
        ob_end_clean();

        // Kiểm tra: sản phẩm phải được xóa khỏi bảng products
        $result = $this->conn->query("SELECT * FROM products WHERE id = 999");
        $this->assertEquals(0, $result->num_rows, "Sản phẩm không được xóa thành công.");
    }

    // Test xóa sản phẩm khi đã có trong đơn hàng
    public function testDeleteProductWithOrder()
    {
        // Thêm dữ liệu test vào bảng products
        $this->conn->query("INSERT INTO products (id, name, price, quantity, category_id, image_url, description, isshow) 
        VALUES (999, 'Sản phẩm xóa', 1000, 10, 999, 'delete_image.jpg', 'Mô tả xóa', 0)");

        // Giả lập thêm sản phẩm vào bảng order_details để mô phỏng sản phẩm đã được đặt
        $this->conn->query("INSERT INTO order_details (product_id, order_id, quantity) VALUES (999, 1, 2)");

        $_GET['id_sp'] = 999;

        ob_start();
        include './adminxoasanpham.php'; // Gọi file xóa sản phẩm
        ob_end_clean();

        // Kiểm tra: sản phẩm vẫn còn trong bảng products nhưng trạng thái isshow = 1 (không hiển thị)
        $result = $this->conn->query("SELECT * FROM products WHERE id = 999");
        $product = $result->fetch_assoc();
        $this->assertEquals(1, $product['isshow'], "Sản phẩm không được cập nhật trạng thái hiển thị.");

        // Dọn dẹp dữ liệu (nếu còn)
        $this->conn->query("DELETE FROM order_details WHERE product_id = 999");
        $this->conn->query("DELETE FROM products WHERE id = 999");
    }

    // Dọn dẹp dữ liệu sau khi test
    protected function tearDown(): void
    {
        // Xóa sản phẩm trước
        $this->conn->query("DELETE FROM products WHERE category_id = 999");
        // Sau đó xóa danh mục
        $this->conn->query("DELETE FROM categories WHERE id = 999");

        // Xóa file hình ảnh test
        if (file_exists(__DIR__ . '/test_image.jpg')) {
            unlink(__DIR__ . '/test_image.jpg');
        }
        if (file_exists(__DIR__ . '/updated_image.jpg')) {
            unlink(__DIR__ . '/updated_image.jpg');
        }

        $this->conn->close();
    }
}
