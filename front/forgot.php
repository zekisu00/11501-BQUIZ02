<fieldset style="width:50%;margin:auto">
    <legend>忘記密碼</legend>
    <table>
        <tr>
            <td>請輸入信箱以查詢密碼</td>
        </tr>
        <tr>
            <td>
                <input type="text" name="email" id="email">
            </td>
        </tr>
        <tr>
            <td id="result"></td>
        </tr>
        <tr>
            <td>
                <button onclick="search()">尋找</button>
            </td>
        </tr>
    </table>
</fieldset>

<script>
/**
 * 函數：search
 * 功能：讀取使用者輸入的信箱，傳送給後端 PHP 處理，並將結果顯示在畫面上。
 */
function search(){
    // 1. 取得使用者在輸入框中輸入的 email 字串
    let email = $("#email").val();
    
    // 2. 使用 jQuery 的 $.get 進行 AJAX 非同步請求
    // 第一個參數是目標 PHP 檔案的路徑
    // 第二個參數是用物件形式傳送的資料 {email: email}
    // 第三個參數是回呼函式 (Callback)，當伺服器成功回傳資料時執行
    $.get("./api/forgot.php", {email}, (result) => {
        // 3. 將伺服器回傳的結果 (result) 填入 id 為 "result" 的 td 元素中
        $("#result").text(result);
    });
}
</script>

// 程式運作流程與邏輯分析
// 為了幫助你更好理解這段程式的運作方式，這裡有一個簡單的流程概念：

// 前端觸發：使用者輸入信箱後點擊「尋找」按鈕，觸發 search() 函式。

// AJAX 請求：JavaScript 透過 $.get 將 email 資料靜默傳送到 ./api/forgot.php。這不會造成網頁重新整理。

// 後端處理（你需要在 ./api/forgot.php 中撰寫）：

// 接收 $_GET['email']。

// 查詢資料庫該信箱對應的密碼。

// echo 出密碼或是「找不到該帳號」的訊息。

// 前端顯示：result 參數接收到後端回傳的字串，並透過 $("#result").text(result) 將訊息即時顯示給使用者看。

// 溫馨提醒（安全性建議）：
// 在實際開發中，直接將密碼透過 $.get 回傳並顯示在網頁上是有風險的（明碼傳輸且容易被攔截）。
// 通常在「忘記密碼」功能中，我們會採取以下較安全的做法：

// 寄送驗證信：後端比對資料庫後，發送一封包含重設密碼連結的 Email 給使用者，而不是直接顯示在網頁上。

// 遮罩處理：如果一定要顯示，建議後端回傳時對密碼進行遮罩（例如 a****123），保護使用者隱私。