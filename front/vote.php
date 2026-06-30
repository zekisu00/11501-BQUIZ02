/* 頁面：問卷投票頁面
 * 此頁面負責顯示問卷題目與對應的單選選項，並透過表單將使用者的選擇發送至後端進行投票處理。
 */
<?php 
// 1. 資料獲取：根據網址參數 id 取得問卷題目，並查詢該問卷下所有的選項
$subject = $Que->find($_GET['id']);
$options = $Que->all(['main_id' => $subject['id']]);
?>

<fieldset>
    <legend>目前位置：首頁 > 問卷調查 > <?= $subject['text'] ?></legend>
    <h3><?= $subject['text'] ?></h3>
    <form action="./api/vote.php" method="post">
        <?php
        // 3. 迴圈渲染：將所有問卷選項以 radio 按鈕形式輸出，讓使用者進行單選
        foreach($options as $option){
            echo "<p>";
            // input 的 value 設定為該選項的 ID，作為後端辨識投票目標的依據
            echo "<input type='radio' name='vote' value='{$option['id']}'>";
            echo $option['text'];
            echo "</p>";
        }
        ?>
        <div class="ct">
            <input type="submit" value="我要投票">
        </div>
    </form>
</fieldset>