/* 頁面：最新文章管理系統
 * 此功能頁面允許管理員對文章進行「批次顯示/隱藏」的設定，以及執行「批次刪除」操作。
 */
<form action="./api/edit_news.php" method="post">
    <table style="width:80%;margin:auto;">
        <tr class="ct">
            <td style="width:10%">編號</td>
            <td>標題</td>
            <td style="width:10%">顯示</td>
            <td style="width:10%">刪除</td>
        </tr>
        <?php
        // 1. 分頁參數計算：取得總文章數並設定每頁顯示 3 筆，計算起始索引 (start)
        $all = $News->count();
        $div = 3;
        $pages = ceil($all / $div);
        $now = $_GET['p'] ?? 1;
        $start = ($now - 1) * $div;
        
        // 2. 資料查詢：依據計算後的索引撈取當前頁次的文章
        $rows = $News->all(" limit $start,$div");
        foreach($rows as $idx => $row):
        ?>
        <tr class="ct">
            <td><?= $start + 1 + $idx ?>.</td>
            <td><?= $row['title']; ?></td>
            <td>
                <input type="checkbox" name="sh[]" value="<?= $row['id']; ?>" <?= ($row['sh']==1)?'checked':''; ?>>
            </td>
            <td>
                <input type="checkbox" name="del[]" value="<?= $row['id']; ?>">
            </td>
        </tr>
        <input type="hidden" name="id[]" value="<?= $row['id']; ?>">
        <?php
        endforeach;
        ?>
    </table>
    <div class="ct">
        <?php 
        if(($now - 1) > 0){
            $prev = $now - 1;
            echo "<a href='?do=news&p=$prev'> < </a>";
        }
        for($i = 1; $i <= $pages; $i++){
            $size = ($now == $i) ? '24px' : '';
            echo "<a href='?do=news&p=$i' style='font-size:$size'> $i </a>";
        }
        if(($now + 1) <= $pages){
            $next = $now + 1;
            echo "<a href='?do=news&p=$next'> > </a>";
        }
        ?>
    </div>
    <div class="ct">
        <input type="submit" value="確定修改">
    </div>
</form>