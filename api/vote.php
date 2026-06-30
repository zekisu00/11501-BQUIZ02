/* 檔案：api/vote.php
 * 功能：處理問卷投票邏輯。
 * 此程式會更新選項的票數，並同時增加所屬問卷題目的總票數，最後導向至結果頁。
 */
<?php 
// 包含資料庫設定檔以存取 $Que 物件
include_once "db.php";

// 1. 取得使用者選擇的選項資料，並將該選項的票數 (vote) 加 1
$option = $Que->find($_POST['vote']);
$option['vote']++;

// 2. 取得該選項所屬的問卷主題目資料，並將主題目的總票數 (vote) 也加 1
$subject = $Que->find($option['main_id']);
$subject['vote']++;

// 3. 將更新後的票數回存至資料庫
$Que->save($option);
$Que->save($subject);

// 4. 投票完成後，跳轉至該問卷的結果頁面顯示統計數據
to("../index.php?do=result&id={$subject['id']}");

/*
這段程式碼展示了關聯式資料庫中「兩層式計票」的設計：

選項層級：紀錄個別項目的得票，用於呈現長條圖分佈。

問卷層級：紀錄總參與次數，方便快速統計總和。
*/