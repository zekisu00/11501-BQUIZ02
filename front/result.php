/* 頁面：問卷調查結果顯示
 * 此程式碼依據網址參數 id 讀取問卷主項目與其選項，並計算各選項的得票比例，以長條圖形式呈現投票結果。
 */
<?php 
// 1. 資料讀取：根據傳入的 ID 抓取問卷題目主項，並查詢所有屬於該題目的選項 (main_id 為題目 ID)
$subject = $Que->find($_GET['id']);
$options = $Que->all(['main_id'=>$subject['id']]);
?>
<fieldset>
    <legend>目前位置：首頁 > 問卷調查 > <?= $subject['text'] ?></legend>
    <h3><?= $subject['text'] ?></h3>
    <?php
    // 2. 迴圈處理：逐一輸出每個選項的結果
    foreach($options as $option){
        // 避免除以零錯誤：若總投票數為 0，將分母設為 1，否則使用總投票數計算百分比
        $div = ($subject['vote'] > 0) ? $subject['vote'] : 1; 
        $rate = $option['vote'] / $div;
        $percent = round($rate * 100);
        // 3. 渲染圖表：使用 Flex 排版建立兩欄結構
        echo "<div style='display:flex'>";
        // 左側顯示選項文字
        echo "<div style='width:40%'>{$option['text']}</div>";
        // 右側顯示統計長條圖與詳細票數 (長條寬度以 75% 比例顯示作為視覺設計)
        echo "<div style='width:60%;display:flex'>";
        echo "<div style='background:#aaa;height:30px;width:".round(0.75 * $rate * 100)."%'></div>";
        echo "<div style='width:75%'>{$option['vote']}票($percent%)</div>";
        echo "</div>";
        echo "</div>";
    }
    ?>
    <div class="ct">
        <button onclick="location.href='?do=que'">返回</button>
    </div>
</fieldset>