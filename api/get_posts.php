<?php
//檔案：api/get_list.php
//功能：根據類別 (type) 查詢對應的所有文章標題，並生成點擊後觸發 JavaScript 的超連結列表。

// 包含資料庫設定檔以存取 $News 物件
include_once "db.php";

// 1. 資料查詢：依據網址參數 type，從資料庫撈取所有符合該類別的文章
$list = $News->all(['type' => $_GET['type']]);

// 2. 迴圈渲染：將文章列表轉換為連結，點擊時呼叫前端 getPost(id) 函式載入文章內容
foreach($list as $l){
    // 使用 javascript: 偽協定來綁定點擊事件，方便後端數據動態更新前端介面
    echo "<a href='javascript:getPost({$l['id']})' style='margin:10px 0;display:block'>";
    echo $l['title'];
    echo "</a>";
}
?>