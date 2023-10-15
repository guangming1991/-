<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["record_id"])) {
    $record_id = $_POST["record_id"];
    $stmt = $conn->prepare("UPDATE file_records SET circulated = TRUE WHERE id = ?");
    $stmt->bind_param("i", $record_id);

    if ($stmt->execute()) {
        echo "传阅状态已更新";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

header("Location: ../index.php");
?>
