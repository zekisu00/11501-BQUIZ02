/* 檔案：api/edit_acc.php
 * 功能：處理會員帳號刪除請求，接收來自表單的刪除清單 (del陣列) 並執行資料庫刪除。
 */
<?php 
// 包含資料庫設定檔以存取 $Mem 物件
include_once "db.php";

// 1. 檢查是否有接收到需要刪除的 ID 陣列
if(isset($_POST['del'])){
    // 2. 迴圈處理：逐一取出被勾選的會員 ID，呼叫 Mem 物件的 del 方法進行刪除
    foreach($_POST['del'] as $id){
        $Mem->del($id);
    }
}

// 3. 完成後導向回帳號管理頁面 (admin.php)
to("../admin.php?do=acc");