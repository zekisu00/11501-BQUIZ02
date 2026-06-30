// JavaScript Document

/**
 * 函數：lo
 * 功能：透過 AJAX 載入指定網址的內容，並放入指定的 HTML 元素中。
 * @param {object} th - 要載入內容的目標 HTML 元素 (通常為 $(this))
 * @param {string} url - 要請求的目標 PHP 或 HTML 檔案路徑
 */
function lo(th, url) {
    // 使用 jQuery 的 $.ajax 方法
    $.ajax(url, {
        cache: false, // 不使用快取，確保每次載入的都是最新資料
        success: function(x) { // 當請求成功時執行
            $(th).html(x); // 將取得的資料 (x) 填入目標元素 (th) 內
        }
    });
}

/* 這段被註解掉的程式碼是關於「按讚/收回讚」的功能實現
   function good(id, type, user)
   {
       // 使用 $.post 發送資料到後端處理
       $.post("back.php?do=good&type="+type, {id, user}, function()
       {
           // 如果 type 為 "1"，表示使用者目前是按讚
           if(type=="1")
           {
               // 將畫面上該文章的按讚數加 1
               $("#vie"+id).text($("#vie"+id).text()*1+1)
               // 將按鈕文字改為「收回讚」，並修改 onclick 事件為「收回讚」的處理邏輯 (type改為2)
               $("#good"+id).text("收回讚").attr("onclick","good('"+id+"','2','"+user+"')")
           }
           else // 如果 type 為 "2"，表示使用者目前是取消讚
           {
               // 將畫面上該文章的按讚數減 1
               $("#vie"+id).text($("#vie"+id).text()*1-1)
               // 將按鈕文字改為「讚」，並修改 onclick 事件為「按讚」的處理邏輯 (type改為1)
               $("#good"+id).text("讚").attr("onclick","good('"+id+"','1','"+user+"')")
           }
       })
   } 
*/