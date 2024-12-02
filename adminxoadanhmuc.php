<?php
include('./config.php');

$id_dm = $_GET['id_dm'];

// Kết nối tới cơ sở dữ liệu
$mysqli = new mysqli('localhost', 'root', '', 'bandongho');

// Kiểm tra kết nối
if ($mysqli->connect_error) {
    die("Kết nối thất bại: " . $mysqli->connect_error);
}

// Kiểm tra xem danh mục có tồn tại hay không
$sql = "SELECT * FROM categories WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $id_dm);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Xử lý khi danh mục không tồn tại
    echo "Danh mục không tồn tại.";
} else {
    // Nếu danh mục tồn tại, thực hiện xóa
    $delete_sql = "DELETE FROM categories WHERE id = ?";
    $stmt = $mysqli->prepare($delete_sql);
    $stmt->bind_param('i', $id_dm);
    if ($stmt->execute()) {
        // Thành công, chuyển hướng
        header('Location: ./admin.php?adminlayout=admindanhmuc&page=' . ($_GET['page'] ?? 1));
        exit();
    } else {
        // Lỗi khi xóa
        echo "Xóa danh mục thất bại.";
    }
}

// Đóng kết nối
$stmt->close();
$mysqli->close();
