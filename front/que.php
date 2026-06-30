<!--
頁面：問卷調查列表 
此程式碼使用 PHP 迴圈將資料庫中的主問卷題目取出，並根據使用者登入狀態顯示對應的投票操作連結。
-->
<fieldset>
    <legend>目前位置：首頁 > 問卷調查</legend>
    <table>
        <tr>
            <th style="width:10%">編號</th>
            <th style="width:60%">問卷題目</th>
            <th style="width:10%">投票總數</th>
            <th style="width:10%">結果</th>
            <th style="width:10%">狀態</th>
        </tr>
        <?php
        // 1. 資料獲取：從 Que 物件取出所有 main_id 為 0 的記錄 (主問卷項目)
        $ques = $Que->all(['main_id'=>0]);
        // 2. 迴圈渲染：逐一輸出問卷題目，並計算序號 (idx+1)
        foreach($ques as $idx => $que):
        ?>
        <tr>
            <td class='ct'><?= $idx + 1 ; ?></td>
            <td><?= $que['text'] ?></td>
            <td class='ct'><?= $que['vote'] ?></td>
            <td class='ct'>
                <a href='?do=result&id=<?= $que['id']; ?>'>結果</a>
            </td>
            <td class='ct'>
                <?php 
                if(isset($_SESSION['login'])){
                    echo "<a href='?do=vote&id={$que['id']}'>";
                    echo "參與投票";
                    echo "</a>";
                }else{
                    echo "請先登入";
                }
                ?>
            </td>
        </tr>
        <?php 
        // 結束 foreach 迴圈
        endforeach;
        ?>
    </table>
</fieldset>