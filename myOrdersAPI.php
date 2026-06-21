<?php
include 'db.php';

$student_id = $_SESSION['student_id'];

$res = $conn->query("
SELECT o.id, m.item_name,
       o.qty,
       o.order_status
FROM orders o
JOIN menu m ON o.item_id = m.id
WHERE o.student_id = $student_id
");

if($res->num_rows > 0){

    while($r = $res->fetch_assoc()){
?>
<div style="margin:10px; padding:15px; background:white; width:400px; margin:auto; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.2);">

<b><?php echo $r['item_name']; ?></b><br>
Qty: <?php echo $r['qty']; ?><br>
Status: <b><?php echo $r['order_status']; ?></b><br>

<?php if($r['order_status'] == "Ready"){ ?>
    <a href="orderComplete.php?id=<?php echo $r['id']; ?>">
        <button style="background:blue;color:white;">Received</button>
    </a>
<?php } ?>

</div>
<br>
<?php
    }

} else {
    echo "<p align='center'>No orders yet.</p>";
}
?>

