<?php
//頁面：新增問卷功能
//此頁面提供一個動態表單，使用者可輸入問卷題目及多個選項，並透過 JavaScript 實時增加選項欄位。
?>

<fieldset>
    <legend>新增問卷</legend>
    <form action="./api/add_que.php" method="post">
        <table style="width:80%;margin:auto">
            <tr>
                <td>問卷名稱</td>
                <td>
                    <input type="text" name="name" id="name">
                </td>
            </tr>
            <tr>
                <td colspan='2'>
                    <div id='option'>
                        選項<input type="text" name="option[]" style="width:60%" >
                        <button type="button" onclick='more()'>更多</button>
                    </div>
                </td>
            </tr>
        </table>
        <div class="ct">
            <input type="submit" value="新增">
            <input type="reset" value="清空">
        </div>
    </form>
</fieldset>
<script>
/* 函式：more() 用於動態插入新的選項輸入框
 * 使用 jQuery 的 before() 方法，在原本的 option 容器之前插入新的選項行
 */
function more(){
 let opt = `<div id='option'>
                選項<input type="text" name="option[]" style="width:60%" >
            </div>`
 $("#option").before(opt)
}
</script>