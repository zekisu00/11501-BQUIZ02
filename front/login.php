<fieldset style="width:50%;margin:auto;">
    <legend>會員登入</legend>
    <table>
        <tr>
            <td>帳號</td>
            <td><input type="text" name="acc" id="acc"></td>
        </tr>
        <tr>
            <td>密碼</td>
            <td>
                <input type="password" name="pw" id="pw">
            </td>
        </tr>
        <tr>
            <td>
                <button type="button" onclick="login()">登入</button>
                <button type="button" onclick="$('#acc,#pw').val('')">清除</button>
            </td>
            <td>
                <a href="?do=forgot">忘記密碼</a>|
                <a href="?do=reg">尚未註冊</a>
            </td>
        </tr>
    </table>
</fieldset>

<script>
/**
 * 函數：login
 * 功能：執行登入檢查邏輯 (兩階段驗證)
 */
function login(){
    // 1. 將輸入的帳號與密碼打包成物件
    let user = {
        "acc": $("#acc").val(),
        "pw": $("#pw").val()
    };

    // 2. 第一階段：檢查帳號是否存在
    $.get("./api/chk_acc.php", user, (res) => {
        // 如果後端回傳數值 > 0，代表帳號存在
        if(parseInt(res) > 0){
            
            // 3. 第二階段：檢查密碼是否正確
            $.post("./api/chk_pw.php", user, (res) => {
                // 如果後端回傳數值 > 0，代表帳號密碼正確
                if(parseInt(res) > 0){
                    // 根據帳號名稱判斷轉跳頁面
                    if(user.acc == 'admin'){
                        location.href = 'admin.php'; // 管理員進入後台
                    } else {
                        location.href = 'index.php?do=main'; // 一般會員回到首頁
                    }
                } else {
                    alert("密碼錯誤");
                    $('#acc,#pw').val(''); // 清空欄位
                }
            });

        } else {
            alert("查無帳號");
            $('#acc,#pw').val(''); // 清空欄位
        }
    });
}
</script>

<!--
程式邏輯架構說明
這個登入流程透過 AJAX 與後端分工合作，為了讓你更好理解，這裡描述其背後的邏輯流程：

兩階段驗證的好處：

明確錯誤回饋：程式可以明確告訴使用者是「帳號不存在」還是「密碼錯誤」。

安全性考量：雖然這對使用者友好，但要小心，若後端處理不當，這可能會變成「帳號列舉攻擊」
（攻擊者可以透過此回應測試哪些帳號存在於系統中）。

安全性建議（重要）：

密碼傳輸：目前的程式碼使用 $.post 發送密碼，這比 $.get 安全（因為資料在 HTTP Request Body 中），
但仍建議務必全程使用 HTTPS 加密傳輸。

驗證機制：實際應用中，後端 chk_pw.php 在比對密碼時，必須使用 password_verify() 等函式，
將輸入的密碼與資料庫中加密後的 Hash 值比對，絕對不要將密碼以明碼存放在資料庫中。
-->