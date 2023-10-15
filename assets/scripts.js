// 全选或反选复选框
document.getElementById('selectAll').addEventListener('click', function() {
    var checkboxes = document.querySelectorAll('input[name="record_id"]');
    for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
    }
});



// 删除选中的记录
function deleteSelected() {
    var selectedIds = [];
    var checkboxes = document.querySelectorAll('input[name="record_id"]:checked');
    for (var checkbox of checkboxes) {
        selectedIds.push(checkbox.value);
    }
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'actions/delete_records.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            alert("已删除选中的记录");
            location.reload(); // 刷新页面以显示更新
        }
    };
    xhr.send('ids=' + selectedIds.join(','));
}

// 根据关键字搜索记录
function searchRecords() {
    var keyword = document.getElementById('searchKeyword').value;
    window.location.href = 'index.php?keyword=' + keyword;
}

// 根据所选字段对记录进行排序
function sortRecords(sortBy) {
    var currentUrl = window.location.href.split('?')[0];  // 获取当前URL，不带查询参数
    window.location.href = currentUrl + '?sort_by=' + sortBy;
}

function toggleCirculatedRecords(linkElement) {
    var currentUrl = window.location.href.split('?')[0];  // 获取当前URL，不带查询参数
    var showCirculated = linkElement.getAttribute("data-show-circulated") === '1';

    // 根据当前状态决定跳转的URL
    var newUrl = showCirculated ? currentUrl : currentUrl + '?circulated=1';
    window.location.href = newUrl;
}

function filterBySender(sender) {
    var currentUrl = window.location.href.split('?')[0];  // 获取当前URL，不带查询参数
    window.location.href = currentUrl + '?sender=' + sender;
}

function filterByCirculation(circulation) {
    var currentUrl = window.location.href.split('?')[0];
    window.location.href = currentUrl + '?circulation=' + circulation;
}

function fillTitle() {
    var fileInput = document.getElementsByName('attachment')[0];
    var titleInput = document.getElementsByName('file_title')[0];    
    
    var filename = fileInput.value.split('\\').pop().split('/').pop();
    titleInput.value = filename.split('.')[0];
}

function showCirculateModal() {
    var modal = document.getElementById('circulateModal');
    modal.style.display = "block";

    // 获取 <span> 元素，设置点击事件关闭模态窗口
    var span = document.getElementsByClassName("close")[0];
    span.onclick = function() {
        modal.style.display = "none";
    }

    // 当用户点击模态窗口之外的地方，关闭它
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}
// 更新传阅状态
function updateCirculated(id) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'actions/update_circulated.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            alert("记录已更新");
            location.reload(); // 刷新页面以显示更新
        }
    };
    xhr.send('record_id=' + id);
}
// 上传
function showModalAndUpdate(id) {
    console.log("showModalAndUpdate has been called with ID:", id);
    // 显示模态窗口
    showCirculateModal();

    // 上传完成后更新传阅状态
    //$('#circulateForm').on('submit', function(event) {
    //    event.preventDefault();
    //    updateCirculated(id);
    //});
}
