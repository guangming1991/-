<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ids"])) {
    $ids = explode(',', $_POST["ids"]);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conn->prepare("DELETE FROM file_records WHERE id IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
    
    if ($stmt->execute()) {
        echo "记录删除成功";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

header("Location: ../index.php");
?>
