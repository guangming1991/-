<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $file_date = $_POST["file_date"];
    $file_type = $_POST["file_type"];
    $file_title = $_POST["file_title"];
    $sender = $_POST["sender"];
    $file = $_FILES["attachment"];
    $file_path = "../uploads/" . basename($file["name"]);
    
    if (move_uploaded_file($file["tmp_name"], $file_path)) {
        $stmt = $conn->prepare("INSERT INTO file_records (file_date, file_type, file_title, sender, attachment) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $file_date, $file_type, $file_title, $sender, $file_path);
        
        if ($stmt->execute()) {
            echo "新记录插入成功";
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "文件上传失败";
    }
}

header("Location: ../index.php");
?>
