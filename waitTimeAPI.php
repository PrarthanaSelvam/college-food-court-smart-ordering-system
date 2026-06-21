<?php
include 'db.php';

// return JSON map of item_id => updated wait time
$result = [];
$res = $conn->query("SELECT id, wait_time FROM menu");

while($r = $res->fetch_assoc()){
    $itemId = $r['id'];
    $base = intval($r['wait_time']);
    // count pending orders (preparing or ready)
    $pqRes = $conn->query("SELECT COALESCE(SUM(qty),0) AS pq FROM orders WHERE item_id=$itemId AND order_status IN ('Preparing','Ready')");
    $pqRow = $pqRes->fetch_assoc();
    $pendingQty = intval($pqRow['pq']);

    // for every 5 pending items add 1 minute
    $extra = ceil($pendingQty / 5);
    $newWait = $base + $extra;
    $result[$itemId] = $newWait;
}

header('Content-Type: application/json');
echo json_encode($result);
