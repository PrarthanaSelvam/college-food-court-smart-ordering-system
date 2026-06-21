<?php
include 'db.php';

if(isset($_GET['oid']) && isset($_GET['st'])){
    $oid = intval($_GET['oid']);
    $status = $_GET['st'];

    $conn->query("UPDATE orders SET order_status='$status' WHERE id=$oid");
}

/* Remove completed order */
if(isset($_GET['remove'])){
    $rid = intval($_GET['remove']);
    $conn->query("DELETE FROM orders WHERE id=$rid");
}

/* crowd update is now automatic; manual overrides removed */
// if(isset($_GET['crowd'])){
//     $c = $_GET['crowd'];
//     $conn->query("UPDATE admin SET crowd_level='$c'");
//}

header("Location: adminOrders.php");
exit();
?>
