<style>
  /* 設定 fieldset 為行內區塊並向上對齊，讓兩個 fieldset 可以左右並排 */
  fieldset{
    display:inline-block;
    vertical-align: top;
  }  
</style>

<div>
    目前位置：首頁 > 分類網誌 > <span class='nav-item'></span>
</div>

<fieldset style="width:150px">
    <legend>分類網誌</legend>
    <div class="type-item" style="cursor:pointer;color:blue;margin:10px 0" >健康新知</div>
    <div class="type-item" style="cursor:pointer;color:blue;margin:10px 0" >菸害防制</div>
    <div class="type-item" style="cursor:pointer;color:blue;margin:10px 0" >癌症防治</div>
    <div class="type-item" style="cursor:pointer;color:blue;margin:10px 0" >慢性病防治</div>
</fieldset>

<fieldset style="width:550px">
    <legend>文章列表</legend>
    <div class="post-list"></div>
    <div class="post-content" style="display:none"></div>
</fieldset>


<script>
// 初始化：頁面載入時，將導航列文字設為第一個分類的名稱
$(".nav-item").text($(".type-item").eq(0).text());

// 初始化：載入第一個分類的文章列表
getPosts($(".type-item").eq(0).text());
    
// 分類點擊事件：當點擊左側分類時
$(".type-item").on("click", function(){
    let text = $(this).text(); // 取得點擊的分類名稱
    $(".nav-item").text(text); // 更新導航列文字
    getPosts(text);            // 呼叫 AJAX 函式抓取該分類文章
});

/**
 * 函式：getPosts
 * 功能：向後端請求該分類的文章列表
 * @param {string} type - 分類名稱
 */
function getPosts(type){
    $.get("./api/get_posts.php", {type}, (list) => {
        $(".post-list").html(list);  // 將回傳的列表填入容器
        $(".post-list").show();      // 確保列表顯示
        $(".post-content").hide();   // 隱藏文章內容區
    });
}

/**
 * 函式：getPost
 * 功能：點擊文章標題時，請求該文章的詳細內容
 * @param {number} id - 文章的唯一識別 ID
 */
function getPost(id){
    $.get("./api/get_post.php", {id}, (post) => {
        $(".post-content").html(post); // 將文章內容填入容器
        $(".post-list").hide();        // 隱藏列表
        $(".post-content").show();     // 顯示詳細內容
    });
}
</script>

<!--
核心運作邏輯分析
這段程式碼運用了前後端分離 (Partial Rendering) 的概念，這在現代網頁開發中非常實用：

狀態管理：透過切換 .post-list (列表) 與 .post-content (詳情) 的 show() 與 hide()，
實現了「單頁應用」(Single Page Application) 的瀏覽體驗，使用者感覺不到頁面重新整理。

數據驅動：

getPosts 負責抓取「清單」（通常會回傳包含 getPost(id) 連結的 HTML）。

getPost 負責抓取「詳細內容」（使用者點擊標題後觸發）。

溫馨提示：
你的這套架構非常完整，但要注意的是：在 get_posts.php 回傳的 HTML 中，
必須確保文章標題的連結是呼叫 onclick="getPost(id)"，這樣才能正確連接到這段 JavaScript 函數。
-->