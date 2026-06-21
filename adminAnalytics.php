<?php
include 'adminCommon.php';

// prepare today's statistics (similar to previous code) if created_at exists
$todayTop = null;
$todayRev = null;
$todayCnt = null;
$where = "";

// assume created_at column present
$where = "DATE(created_at)=CURDATE()";
$topRes = $conn->query(
    "SELECT m.item_name, SUM(o.qty) AS total_qty
     FROM orders o
     JOIN menu m ON o.item_id=m.id
     WHERE $where
     GROUP BY o.item_id
     ORDER BY total_qty DESC
     LIMIT 1"
);
if($topRes && $topRes->num_rows > 0){
    $todayTop = $topRes->fetch_assoc();
}
$revRes = $conn->query("SELECT COALESCE(SUM(total),0) AS revenue FROM orders WHERE $where");
$todayRev = $revRes->fetch_assoc()['revenue'];
$cntRes = $conn->query("SELECT COUNT(*) AS cnt FROM orders WHERE $where");
$todayCnt = $cntRes->fetch_assoc()['cnt'];
?>

<link rel="stylesheet" href="style.css">

<h1 align="center">Analytics</h1>
<div align="right" style="margin-right:20px;">
    <a href="admin.php"><button>Back</button></a>
</div>
<br>
<div style="width:80%; margin:auto; background:#efefef; padding:20px; border-radius:15px;">
    <h2 align="center">Analytics</h2>
    <?php
    echo "<h3>Today's Performance</h3>";
    echo "Top seller: ".($todayTop ? $todayTop['item_name'] : 'N/A')."<br>";
    echo "Revenue: ₹".number_format($todayRev,2)."<br>";
    echo "Orders: $todayCnt<br><br>";
    // top selling items overall
    $aRes = $conn->query(
        "SELECT m.item_name, SUM(o.qty) AS total_qty
         FROM orders o
         JOIN menu m ON o.item_id=m.id
         GROUP BY o.item_id
         ORDER BY total_qty DESC"
    );
    if($aRes->num_rows > 0){
        echo "<table width='100%' border='1' cellpadding='5' style='border-collapse:collapse; text-align:center;'>";
        echo "<tr><th>Item</th><th>Total Qty Sold</th></tr>";
        while($ar = $aRes->fetch_assoc()){
            echo "<tr><td>{$ar['item_name']}</td><td>{$ar['total_qty']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p align='center'>No sales data yet.</p>";
    }
    // low stock alert
    $lsRes = $conn->query("SELECT item_name, available_qty FROM menu WHERE available_qty <= 5 AND status!='Sold Out'");
    if($lsRes->num_rows > 0){
        echo "<h3 style='color:orange;'>Low Stock Alerts:</h3>";
        echo "<ul>";
        while($ls = $lsRes->fetch_assoc()){
            echo "<li>{$ls['item_name']} (only {$ls['available_qty']} left)</li>";
        }
        echo "</ul>";
    }
    ?>
</div>
