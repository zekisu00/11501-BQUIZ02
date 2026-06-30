
<?php
/*
檔案：api/logout.php
功能：處理使用者登出邏輯。
*/

// 包含資料庫設定檔 (雖然此頁面未直接使用 DB 物件，但通常會保留以維持一致性)
include_once "db.php";

// 1. 登出作業：使用 unset() 清除 Session 中的登入標記
unset($_SESSION['login']);

// 2. 頁面導向：跳轉回網站首頁 (index.php)
to("../index.php");