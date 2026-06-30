<?php 
session_start();
date_default_timezone_set("Asia/Taipei");

/**
 * DB 類別：封裝 PDO 資料庫操作
 */
class DB {
    protected $dsn = "mysql:host=localhost;charset=utf8;dbname=db19"; // 資料庫連線設定
    protected $pdo; // PDO 物件
    protected $table; // 當前操作的資料表名稱

    // 建構子：初始化物件時指定資料表名稱並建立 PDO 連線
    function __construct($table) {
        $this->table = $table;
        $this->pdo = new PDO($this->dsn, 'root', '');
    }

    // 查詢多筆資料：支援陣列條件與字串條件 (如: order by, limit)
    function all(...$arg) {
        $sql = "SELECT * FROM $this->table ";
        if (isset($arg[0])) {
            if (is_array($arg[0])) {
                $tmp = $this->a2s($arg[0]); // 將陣列轉換為 SQL 條件字串
                $sql .= " WHERE " . join(" AND ", $tmp);
            } else {
                $sql .= $arg[0]; // 直接串接字串 (如: " limit 0,5")
            }
        }
        if (isset($arg[1])) { $sql .= $arg[1]; }
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // 計算數量：用途同 all，但 SELECT 改為 count(*)
    function count(...$arg) {
        $sql = "SELECT count(*) FROM $this->table ";
        if (isset($arg[0])) {
            if (is_array($arg[0])) {
                $tmp = $this->a2s($arg[0]);
                $sql .= " WHERE " . join(" AND ", $tmp);
            } else {
                $sql .= $arg[0];
            }
        }
        if (isset($arg[1])) { $sql .= $arg[1]; }
        return $this->pdo->query($sql)->fetchColumn();
    }

    // 查詢單筆資料：根據 ID 或陣列條件
    function find($arg) {
        $sql = "SELECT * FROM $this->table ";
        if (is_array($arg)) {
            $tmp = $this->a2s($arg);
            $sql .= " WHERE " . join(" AND ", $tmp);
        } else {
            $sql .= " WHERE `id`='$arg'";
        }
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    // 新增或更新資料：若陣列中包含 id 則執行 update，否則執行 insert
    function save($arg) {
        if (isset($arg['id'])) {
            $tmp = $this->a2s($arg);
            $sql = "UPDATE $this->table SET " . join(" , ", $tmp);
            $sql .= " WHERE `id`='{$arg['id']}'";
        } else {
            $keys = array_keys($arg);
            $sql = "INSERT INTO $this->table (`" . join("`,`", $keys) . "`) VALUES('" . join("','", $arg) . "');";
        }
        return $this->pdo->exec($sql);
    }

    // 刪除資料：根據 ID 或陣列條件
    function del($arg) {
        $sql = "DELETE FROM $this->table ";
        if (is_array($arg)) {
            $tmp = $this->a2s($arg);
            $sql .= " WHERE " . join(" AND ", $tmp);
        } else {
            $sql .= " WHERE `id`='$arg'";
        }
        return $this->pdo->exec($sql);
    }

    // 輔助函式：將陣列轉為 SQL 的 `欄位`='值' 格式陣列
    protected function a2s($array) {
        $tmp = [];
        foreach ($array as $key => $val) {
            $tmp[] = "`$key`='$val'";
        }
        return $tmp;
    }

    // 原始 SQL 執行：當上述方法不足以應付時使用
    function q($sql) {
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}

// 實用除錯函式
function dd($array) {
    echo "<pre>"; print_r($array); echo "</pre>";
}

// 頁面跳轉函式
function to($url) {
    header("location:$url");
}

// 初始化各資料表物件
$Mem = new DB('members');
$Total = new DB('total');
$News = new DB('news');
$Que = new DB("que");
$Log = new DB('logs');

// 網站訪客計數器邏輯
if (!isset($_SESSION['total'])) {
    $total = $Total->find(['date' => date("Y-m-d")]);
    if (!empty($total)) {
        $total['total']++;
        $Total->save($total);
        $_SESSION['total'] = $total['total'];
    } else {
        $Total->save(['date' => date("Y-m-d"), 'total' => 1]);
        $_SESSION['total'] = 1;
    }
}

/*
核心運作概念
這段程式碼採用了物件導向 (OOP) 的設計來隔離 SQL 語法與業務邏輯：

靈活的參數傳遞：利用 ...$arg (變數長度參數) 讓 all() 和 count() 
可以根據需求選擇「只傳條件」或「傳條件+SQL語句」。

陣列轉 SQL (a2s)：這是此類別最聰明的地方，它自動將 PHP 關聯陣列轉換為 SQL 的 WHERE 條件句，
省去手動寫 WHERE a=b AND c=d 的麻煩。
*/