<?php
$servername = "192.168.2.100";
$username = "root";
$password = "123456";
$dbname = "wjcy";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
?>
