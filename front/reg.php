/* 頁面：會員註冊系統 
 * 此程式碼包含註冊表單結構與前端驗證邏輯，透過 AJAX 與後端 API 進行帳號重複檢查與資料寫入。
 */
<fieldset style="width:50%;margin:auto;">
    <legend>會員註冊</legend>
        <div style='color:red'>*請設定您要註冊的帳號及密碼(最長12個字元)</div>
        <table>
            <tr>
                <td>Step1:登入帳號</td>
                <td><input type="text" name="acc" id="acc"></td>
            </tr>
            <tr>
                <td>Step2:登入密碼</td>
                <td><input type="password" name="pw" id="pw"></td>
            </tr>
            <tr>
                <td>Step3:再次確認密碼</td>
                <td><input type="password" name="pw2" id="pw2"></td>
            </tr>
            <tr>
                <td>Step4:信箱(忘記密碼時使用)</td>
                <td><input type="text" name="email" id="email"></td>
            </tr>
            <tr>
                <td>
                    <button type="button" onclick="reg()">註冊</button>
                    <button type="button" onclick="$('#acc,#pw,#pw2,#email').val('')">清除</button>
                </td>
                <td></td>
            </tr>
        </table>
</fieldset>
<script>
/* 函式：reg() 執行前端基礎檢核後，透過 AJAX 請求後端進行註冊 */
function reg(){
    // 1. 將輸入框的值包裝成物件，方便後續傳輸
    let user = {
        'acc':$("#acc").val(),
        'pw':$("#pw").val(),
        'pw2':$("#pw2").val(),
        'email':$("#email").val()
    }
    // 2. 表單驗證：檢查欄位是否為空值，或兩次密碼輸入是否不一致
    if(user.acc=="" || user.pw=="" || user.pw2=="" || user.email==""){
        alert("不可空白");
    }else if(user.pw != user.pw2){
        alert("密碼錯誤");
    }else{
        // 3. 檢查帳號重複性：向後端 API 請求確認該帳號是否已存在
        $.get("api/chk_acc.php",user,(res)=>{
            if(parseInt(res)>0){
                alert("帳號重複");
            }else{
                // 4. 執行註冊：若帳號未重複，發送 POST 請求寫入資料庫，成功後自動跳轉至登入頁
                $.post("api/reg.php",user,()=>{
                     alert("註冊成功，歡迎加入")
                     location.href='?do=login'
                })
            }
        })
    }
}
</script>