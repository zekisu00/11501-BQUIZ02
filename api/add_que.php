<?php 
//檔案：api/add_que.php
//功能：處理問卷新增請求，先儲存問卷題目，再逐一儲存其對應的選項。

// 包含資料庫設定檔以存取 Que 物件
include_once "db.php";

// 1. 檢查問卷名稱是否存在且不為空
if(isset($_POST['name']) && $_POST['name'] != ""){
    // 2. 儲存問卷主項目 (main_id 為 0 代表這是題目)
    $Que->save([
        'text' => $_POST['name'],
        'main_id' => 0,
        'vote' => 0
    ]);

    // 3. 取得剛才新增的問卷題目 ID，作為後續選項歸類的關聯鍵
    $main_id = $Que->find(['text' => $_POST['name']])['id'];

    // 4. 檢查是否有傳入選項資料
    if(isset($_POST['option'])){
        // 5. 迴圈處理所有選項：若選項內容不為空，則存入資料庫，並將 main_id 指向剛新增的題目 ID
        foreach($_POST['option'] as $option){
            if($option != ""){
                $Que->save([
                    'text' => $option,
                    'main_id' => $main_id,
                    'vote' => 0
                ]);
            }
        }
    }
}

// 6. 操作完成後，跳轉回管理頁面 (admin.php) 的問卷區
to("../admin.php?do=que");