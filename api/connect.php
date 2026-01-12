<?php
// $dsn = "mysql:host=localhost;dbname=finsljft_db;charset=utf8";
// $username = "finsljft_user";
// $password = "G2gNxFg^G^J-";
// local---------------------------
$dsn = "mysql:host=localhost;dbname=finshop_db;charset=utf8";
$username = "root";
$password = "";

try {
    $connect = new PDO($dsn, $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "เกิดข้อผิดพลาดในการเชื่อมต่อกับฐานข้อมูล: " . $e->getMessage();
    exit();
}

?>