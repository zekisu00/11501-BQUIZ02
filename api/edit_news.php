/* 檔案：api/edit_news.php
 * 功能：處理最新文章管理的批次更新與刪除請求。
 * 邏輯：遍歷所有接收到的文章 ID，判斷該 ID 是否在刪除清單 (del) 中，
 * 若在則執行刪除；否則檢查是否在顯示清單 (sh) 中，以更新顯示狀態。
 */
<?php 
// 包含資料庫設定檔以存取 $News 物件
include_once "db.php";

// 1. 遍歷所有頁面上存在的文章 ID (透過表單隱藏欄位傳入)
foreach($_POST['id'] as $id){
    // 2. 刪除邏輯：若該 ID 出現在刪除陣列中，直接呼叫 del() 進行刪除
    if(isset($_POST['del']) && in_array($id, $_POST['del'])){
        $News->del($id);
    }else{
        // 3. 更新邏輯：若非刪除，則讀取該文章現有資料
        $news = $News->find($id);
        
        // 4. 狀態判斷：檢查該 ID 是否在顯示陣列 (sh) 中
        // 若有勾選，則將 sh 設為 1，反之設為 0
        $news['sh'] = (isset($_POST['sh']) && in_array($id, $_POST['sh'])) ? 1 : 0;
        
        // 5. 儲存更新後的文章狀態
        $News->save($news);
    }
}

// 6. 操作結束後導向回文章管理頁面
to("../admin.php?do=news");

/*
這段程式碼採用了常見的批次表單處理模式，透過隱藏欄位 id[] 確保即使 checkbox 為未勾選狀態
（未勾選的 checkbox 不會傳送任何值），後端也能完整接收到每一筆文章的狀態。
*/