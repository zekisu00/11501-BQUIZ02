/* 檔案：api/good.php
 * 功能：處理文章按讚/收回讚的邏輯。
 * 此程式會檢查使用者是否已對該文章按過讚，若有則取消按讚並扣除讚數，否則新增紀錄並增加讚數。
 */
<?php 
// 包含資料庫設定檔以存取 $Log 與 $News 物件
include_once "db.php";

// 1. 檢查該使用者是否已對此文章按過讚
$chk = $Log->count(['user' => $_SESSION['login'], 'news' => $_POST['id']]);
// 2. 獲取該文章的當前資料
$post = $News->find($_POST['id']);

// 3. 邏輯判斷：根據是否已存在按讚紀錄進行對應操作
if($chk){
    // 已按過讚：從 Log 資料表刪除紀錄，並將文章讚數減 1
    $Log->del(['user' => $_SESSION['login'], 'news' => $_POST['id']]);
    $post['good']--;
}else{
    // 未按過讚：新增一筆紀錄到 Log 資料表，並將文章讚數加 1
    $Log->save(['user' => $_SESSION['login'], 'news' => $_POST['id']]);
    $post['good']++;
}

// 4. 更新 News 資料表中的讚數欄位
$News->save($post);

/*
這段程式碼透過 Log 資料表作為中間層，
確保了每位使用者對每篇文章只能有一筆按讚紀錄，這是一種常見的「點讚系統」資料庫設計邏輯。
*/