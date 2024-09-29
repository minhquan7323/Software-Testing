<?php
session_start();
if (isset($_GET['id_sp'])) {
    $id_sp = $_GET['id_sp'];
}

if (isset($_SESSION['id_user'])) {
    if (isset($_SESSION['giohang'][$_SESSION['id_user']][$id_sp])) {
        $_SESSION['giohang'][$_SESSION['id_user']][$id_sp] = $_SESSION['giohang'][$_SESSION['id_user']][$id_sp] + 1;
    } else {
        $_SESSION['giohang'][$_SESSION['id_user']][$id_sp] = 1;
    }
    // echo $_SESSION['giohang'][$id_sp];
    // echo array_sum($_SESSION['giohang']);
    header('location: ./index.php?layout=giohang');
    exit();
} else {
    $_SESSION['khongmuahang'] = 1;
    $_SESSION['nutchitietsp'] = 1;
    header('location: ./index.php?layout=trangchu');
    exit();
}
