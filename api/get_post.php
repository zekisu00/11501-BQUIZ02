<?php 
//檔案：api/get_news.php
//功能：讀取並顯示指定文章的內容，透過 nl2br 函數將資料庫中的換行符號轉換為 HTML 的 <br> 標籤。

// 包含資料庫設定檔以存取 $News 物件
include_once "db.php";

// 1. 根據網址傳入的 id 參數，從資料庫中取得該文章的詳細資料
$post = $News->find($_GET['id']);

// 2. 顯示內容：使用 nl2br() 處理內容，確保顯示時能保留原始的換行排版
echo nl2br($post['content']);
?>