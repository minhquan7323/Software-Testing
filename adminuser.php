<?php
include('./config.php');
$page;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$rowperpage = 3;
$perRow = $page * $rowperpage - $rowperpage;
$sqlphantrang = "SELECT * FROM users ORDER BY is_admin ASC LIMIT $perRow,$rowperpage";
$queryphantrang = mysqli_query($conn, $sqlphantrang);

$totalRow = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
$totalPage = ceil($totalRow / $rowperpage);

$listPage = "";
for ($i = 1; $i <= $totalPage; $i++) {
    if ($page == $i) {
        $listPage .= '<a href="#" class="phan_trang active" style="background-color: #0077FF">' . $i . '</a>';
    } else {
        $listPage .= '<a href="./admin.php?adminlayout=adminnguoidung&page=' . $i . '" class="phan_trang">' . $i . '</a>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Admin</title>
</head>

<body>
    <div class="content">
        <div class="row">
            <div class="title col-12 box_shadow">
                <b>Quản lý người dùng</b>
            </div>
        </div>
        <div class="row main_frame box_card box_shadow">
            <div class="element_btn">
                <div>
                    <a href="./admin.php?adminlayout=themadmin" class="addnew_btn"><i class="fas fa-plus"></i>Thêm Admin</a>
                </div>
            </div>

            <div class="table-responsive-lg">
                <table class="table table-bordered">
                    <form action="" method="post">
                        <thead>
                            <tr class="table-secondary">
                                <th scope="col">Tài khoản</th>
                                <th scope="col">Mật khẩu</th>
                                <th scope="col">Email</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Khóa/Mở</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($queryphantrang)) {
                                if($row['status']==1){
                                    $khoa = 'Đã khóa';
                                }else{
                                    $khoa = 'Đang mở';
                                }

                                if($row['is_admin']==1){
                                    $isadmin = 'Admin';
                                }else{
                                    $isadmin = 'User';
                                }

                                echo '<tr>
                       <td>' . $row['username'] . '</td>
                       <td>' . $row['password'] . '</td>
                       <td>' . $row['email'] . '</td>
                       <td>' . $khoa . '</td>
                       <td><a href="./adminkhoauser.php?page='.$page.'&id_user=' . $row['id'] . '" class="addnew_btn">Khóa/Mở</a></td>
                   </tr>';
                            }

                            ?>


                        </tbody>
                        <div>
                            <button type="submit" name="trang_thai" class="btn btn-outline-success" style="width:100px;">Cập nhật</button>
                        </div>
                    </form>
                </table>
            </div>
            <div class="category_paging">
                <?php
                echo $listPage;
                ?>
            </div>
        </div>
    </div>
    <!-- <script>
        function confirmAlert() {
            if (confirm("Bạn có muốn xóa người dùng này không?")) return true
            else return false
        }
    </script> -->

</body>

</html>