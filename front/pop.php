/* * 頁面：人氣文章排行榜 
 * 此程式碼區塊包含 PHP 伺服器端的分頁排序查詢、HTML 表格渲染，以及 jQuery 前端互動功能。
 */

<fieldset>
    <legend>目前位置：首頁 > 人氣文章區</legend>
    <table style="width:95%;margin:auto">
        <tr>
            <th style="width:25%" class='ct'>標題</th>
            <th style="width:50%" class='ct'>內容</th>
            <th style="width:25%" class='ct'>人氣</th>
        </tr>
        <?php 
        // 1. 分頁參數計算：計算總文章數，設定每頁筆數 (5)，並根據 URL 參數取得當前頁碼
        $total = $News->count(['sh'=>1]);
        $div = 5;
        $pages = ceil($total / $div);
        $now = $_GET['p'] ?? 1;
        $start = ($now - 1) * $div;
        
        // 2. 資料查詢：依據按讚數 (good) 由高到低排序 (desc)，並套用分頁 LIMIT 限制
        $posts = $News->all(['sh'=>1], " order by good desc limit $start,$div");
        foreach($posts as $post):
        ?>
        <tr>
            <td class="post-title" style="cursor:pointer;"><?= $post['title'] ?></td>
            <td style="position:relative">
                <span><?= mb_substr($post['content'], 0, 30); ?>...</span>
                <div class="alerr" style="display:none; position:absolute; z-index:99; background:#fff; border:1px solid #ccc; padding:10px;">
                    <h3 style='color:lightblue'><?= $post['type'] ?></h3>
                    <pre class="ssaa"><?= nl2br($post['content']) ?></pre>
                </div>
            </td>
            <td>
                <span class="good-sum"><?= $post['good']; ?></span> 個人說    
                <span class="good"></span>
                <?php
                if(isset($_SESSION['login'])){
                    echo "-<a href='#' onclick='good({$post['id']},this)'>";
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
        if($now - 1 > 0){ $prev = $now - 1; echo "<a href='?do=pop&p=$prev'> < </a>"; }
        for($i = 1; $i <= $pages; $i++){
            $size = ($i == $now) ? '24px' : '18px';
            echo "<a href='?do=pop&p=$i' style='font-size:$size'> $i </a>";
        }
        if($now + 1 <= $pages){ $next = $now + 1; echo "<a href='?do=pop&p=$next'> > </a>"; }
    ?>
    </div>
</fieldset>

<script>
// 1. 互動效果：設定標題滑鼠懸浮事件，觸發浮動視窗顯示，並關閉其他視窗以避免重疊
$(".post-title").hover(
    function(){ $(".alerr").hide(); $(this).next("td").children('.alerr').show(); },
    function(){ $(".alerr").hide(); }
)
// 2. 懸浮視窗保持：當滑鼠移入浮動視窗內部時，保持該視窗顯示狀態
$(".alerr").hover(
    function(){ $(this).show(); },
    function(){ $(".alerr").hide(); }
)
// 3. 按讚功能：使用 AJAX 請求伺服器更新資料庫，成功後重新整理頁面以更新 UI (包含註解掉的局部更新備選方案)
function good(id, dom){
    let num = $(dom).parent().find(".good-sum").text() * 1;
    $.post("./api/good.php", {id}, () => {
        location.reload(); 
    })
}
</script>

/*
理解核心互動機制
這段程式碼體現了動態網頁互動的兩個重點：

懸浮工具提示 (Tooltip Hover Effect)：

利用 relative (父容器) 與 absolute (懸浮框) 的定位，達成文字一滑入就彈出詳細內容的效果。
這類設計能節省頁面空間。

狀態同步策略：

在 good() 函式中，你選擇了 location.reload() 而非手動計算數字。

優點：確保使用者看到的數字永遠是伺服器最新的真實狀態。
缺點：頁面會閃爍重新整理，使用者體驗稍弱。

建議：未來若追求流暢體驗，可考慮使用前端判斷邏輯（即你註解掉的 switch），並搭配 AJAX 回傳的新數值進行局部更新。

你對於 position:absolute 的懸浮視窗在排版上有遇到被遮蔽的問題嗎？
如果有的話，記得檢查 CSS 的 z-index 設定是否足夠大。
*/