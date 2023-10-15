<?php
include 'config.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'file_date';  // 默认按日期排序
$showCirculatedOnly = isset($_GET['circulated']) && $_GET['circulated'] == '1';
$sender_filter = isset($_GET['sender']) ? $_GET['sender'] : '';
$circulation_filter = isset($_GET['circulation']) ? $_GET['circulation'] : '';


?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>技保室文件传阅系统</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<h1>技保室文件传阅系统</h1>
<div id="upload-card">
    <h2>上传文件</h2>
    <form action="actions/upload.php" method="post" enctype="multipart/form-data">
        日期: <input type="date" name="file_date" required value="<?php echo date('Y-m-d'); ?>"><br><br>
        附件: <input type="file" name="attachment" required onchange="fillTitle()"><br><br>
        标题: <input type="text" name="file_title" required><br><br>
        类型: 
        <select name="file_type">
            <option value="安全通告">安全通告</option>
            <option value="政策文件">政策文件</option>
            <option value="法规变更">法规变更</option>
        </select><br><br>
        发件人: 
        <select name="sender">
            <option value="民航局">民航局</option>
            <option value="管理局及监管局">管理局及监管局</option>
            <option value="机场集团">机场集团</option>
            <option value="公司">公司</option>
            <option value="部门">部门</option>
        </select><br><br>
        <input type="submit" value="上传记录">
    </form>
</div>


<h2>文件传阅记录</h2>
<input type="text" id="searchKeyword" placeholder="输入标题关键字">
<button onclick="searchRecords()">查询</button>

<table>
    <thead>
    <tr>
        <th><input type="checkbox" id="selectAll"></th>
        <th><a href="#" onclick="sortRecords('file_date')">日期</a></th>
        <th><a href="#" onclick="sortRecords('file_type')">类型</a></th>
        <th>标题</th>
        <th>
            发件人
            <select onchange="filterBySender(this.value)">
                <option value="民航局">民航局</option>
                <option value="管理局及监管局">管理局及监管局</option>
                <option value="机场集团">机场集团</option>
                <option value="公司">公司</option>
                <option value="部门">部门</option>
                <!-- ... -->
            </select>
        </th>
        <th>附件</th>
        <th>
            传阅状态
            <a href="#" onclick="filterByCirculation('circulated')">已传阅</a> | 
            <a href="#" onclick="filterByCirculation('not_circulated')">未传阅</a>
        </th>
    </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM file_records";
        if ($keyword) {
            $sql .= " WHERE file_title LIKE '%$keyword%'";
        }
        if ($showCirculatedOnly) {
            $sql .= $keyword ? " AND circulated = TRUE" : " WHERE circulated = TRUE";
        }
        if ($sender_filter) {
            $sql .= " WHERE sender = '$sender_filter'";
        }
        
        if ($circulation_filter) {
            $condition = "circulated = " . ($circulation_filter == "circulated" ? "TRUE" : "FALSE");
            $sql .= $sender_filter ? " AND $condition" : " WHERE $condition";
        }
        
        $sql .= " ORDER BY $sort_by DESC";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><input type='checkbox' name='record_id' value='" . $row["id"] . "'></td>";
            echo "<td>" . $row["file_date"] . "</td>";
            echo "<td>" . $row["file_type"] . "</td>";
            echo "<td>" . $row["file_title"] . "</td>";
            echo "<td>" . $row["sender"] . "</td>";
            echo "<td><a href='" . $row["attachment"] . "'>下载</a></td>";
            echo "<td>" . ($row["circulated"] ? "已传阅" : "未传阅") . "</td>";
            //echo "<td><button onclick=\"updateCirculated(" . $row["id"] . ")\">传阅</button></td>";
            echo "<td><button onclick=\"showModalAndUpdate(" . $row["id"] . ")\">传阅</button></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<div id="footer">
    <p>显示记录: <?php echo $result->num_rows; ?> / 总条数: <?php echo $result->num_rows; ?></p>
    <button onclick="deleteSelected()">删除</button>
</div>


<div id="circulateModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>上传传阅内容</p>
        <form id="circulateForm" action="actions/circulate.php" method="post" enctype="multipart/form-data">
            <input type="file" name="circulateFile" required>
            <input type="submit" value="上传">
        </form>
    </div>
</div>

<script src="assets/scripts.js"></script>

</body>
</html>
