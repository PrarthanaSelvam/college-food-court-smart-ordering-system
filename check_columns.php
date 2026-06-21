<?php
include 'db.php';
$res = $conn->query("SHOW COLUMNS FROM orders");
while($row = $res->fetch_assoc()){
    echo $row['Field'] . ' - ' . $row['Type'] . "\n";
}
?>