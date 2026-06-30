<fieldset>
    <legend>目前位置：首頁 > 最新文章區</legend>
    <table style="width:95%;margin:auto">
        <tr>
            <th style="width:25%" class='ct'>標題</th>
            <th style="width:50%" class='ct'>內容</th>
            <th style="width:25%" class='ct'></th>
        </tr>
        <?php 
        // --- 分頁邏輯計算 ---
        $total = $News->count(['sh'=>1]); // 計算顯示中的文章總數
        $div = 5;                        // 設定每頁顯示 5 筆
        $pages = ceil($total / $div);     // 計算總頁數
        $now = $_GET['p'] ?? 1;          // 取得當前頁碼，預設為第 1 頁
        $start = ($now - 1) * $div;      // 計算 SQL LIMIT 的起始值
        
        // 抓取當前頁需要的資料
        $posts = $News->all(['sh'=>1], " limit $start,$div");
        
        foreach($posts as $post):
        ?>
        <tr>
            <td class="post-title" style="cursor:pointer;"><?= $post['title'] ?></td>
            <td>
                <span><?= mb_substr($post['content'], 0, 30); ?>...</span>
                <span style="display:none"><?= nl2br($post['content']) ?></span>
            </td>
            <td>
                <?php
                // 若有登入，顯示按讚功能
                if(isset($_SESSION['login'])){
                    echo "<a href='javascript:good({$post['id']})'>";
                    // 檢查使用者是否已經對該文章按過讚
                    $chk = $Log->count(['user'=>$_SESSION['login'], 'news'=>$post['id']]);
                    echo ($chk) ? "收回讚" : "讚";
                    echo "</a>";
                }
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div>
    <?php 
        // 顯示上一頁按鈕
        if($now - 1 > 0){
            $prev = $now - 1;
            echo "<a href='?do=news&p=$prev'> < </a>";
        }

        // 循環生成頁碼連結
        for($i = 1; $i <= $pages; $i++){
            // 當前頁字體放大以供識別
            $size = ($i == $now) ? '24px' : '18px';
            echo "<a href='?do=news&p=$i' style='font-size:$size'> $i </a>";
        }

        // 顯示下一頁按鈕
        if($now + 1 <= $pages){
            $next = $now + 1;
            echo "<a href='?do=news&p=$next'> > </a>";
        }
    ?>
    </div>
</fieldset>

<script>
// 1. 標題點擊展開功能
$(".post-title").on("click", function(){
    // 切換顯示下一個 td 內部的所有 span 元素 (摘要 <-> 內文)
    $(this).next('td').children('span').toggle();
})

// 2. 按讚功能 (AJAX)
function good(id){
    // 將文章 id 發送到後端處理 (存入 Log 資料表)
    $.post("./api/good.php", {id}, () => {
        // 成功後重新整理頁面以更新按讚狀態
        location.reload();
    })
}
</script>

/*
理解核心概念
為了讓你更好地掌握這段代碼，這裡有兩個關鍵技術的說明：

資料分頁 (Pagination)：

計算 start = (now - 1) * div 是分頁的核心公式。例如：第 1 頁從第 0 筆開始，第 2 頁從第 5 筆開始。

透過 SQL 的 LIMIT 語法，我們只從資料庫撈取當前頁面所需的少量資料，這是提升網站效能的關鍵。

狀態切換 (Toggle)：

你在 HTML 中放置了兩組 <span>（一個摘要，一個完整內容），利用 display:none 隱藏完整內容。

JavaScript 的 .toggle() 方法非常巧妙，它會自動判斷目前狀態並切換「隱藏/顯示」，
這種方式不需要額外重新請求伺服器，使用者體驗極佳。
*/