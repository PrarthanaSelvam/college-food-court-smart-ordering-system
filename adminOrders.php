<?php
include 'adminCommon.php';
?>

<link rel="stylesheet" href="style.css">

<h1 align="center">Student Orders</h1>
<div align="right" style="margin-right:20px;">
    <a href="admin.php"><button>Back</button></a>
</div>
<br>

<div style="width:80%; margin:auto; background:white; padding:20px; border-radius:15px;">

<table width="100%" border="1" cellpadding="10" style="border-collapse:collapse; text-align:center;">
<tr>
    <th>Student</th>
    <th>Item</th>
    <th>Qty</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php
$res = $conn->query("
SELECT o.id,o.qty,o.order_status,
s.name,m.item_name
FROM orders o
JOIN students s ON o.student_id=s.id
JOIN menu m ON o.item_id=m.id
");

while($r = $res->fetch_assoc()){
?>
<tr>
<td><?php echo $r['name']; ?></td>
<td><?php echo $r['item_name']; ?></td>
<td><?php echo $r['qty']; ?></td>
<td><?php echo $r['order_status']; ?></td>
<td>
<a href="adminUpdate.php?oid=<?php echo $r['id']; ?>&st=Ready">
<button style="background:green;color:white;">READY</button>
</a>

<a href="adminUpdate.php?oid=<?php echo $r['id']; ?>&st=Preparing">
<button style="background:orange;color:white;">PREPARING</button>
</a>

<a href="adminUpdate.php?remove=<?php echo $r['id']; ?>">
<button style="background:red;color:white;">REMOVE</button>
</a>
</td>
</tr>
<?php } ?>

</table>
</div>