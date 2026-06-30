<?php 
//檔案：api/chk_pw.php
//功能：驗證會員登入密碼是否正確，並管理登入狀態 (Session)。

// 包含資料庫設定檔以存取 $Mem 物件
include_once "db.php";

// 1. 資料驗證：將前端傳入的帳號與密碼 (POST) 直接傳入 count() 進行比對
// 只要資料庫中有一筆帳號密碼完全吻合，回傳值即為 1
$chk = $Mem->count($_POST);

// 2. 判斷與狀態管理
if($chk){
    // 驗證成功：回傳 1 給前端，並將帳號存入 $_SESSION['login'] 建立登入狀態
    echo 1;
    $_SESSION['login'] = $_POST['acc'];
}else{
    // 驗證失敗：回傳 0 給前端
    echo 0;
}

/*
這段程式碼處理了登入的核心安全邏輯，透過 $_SESSION 來記住使用者的登入狀態，
這樣後續頁面才能透過 isset($_SESSION['login']) 判斷該使用者是否具備權限。

不過，為了安全性，請注意兩點：

密碼加密：在資料庫比對時，不建議使用 $Mem->count($_POST) 直接比對明碼，
應使用 PHP 的 password_verify() 函數。

SQL Injection 防護：確保你的 db.php 中的 count() 方法內部有使用 PDO 的預備語句 (Prepared Statements) 
來防止 SQL 注入攻擊。
*/