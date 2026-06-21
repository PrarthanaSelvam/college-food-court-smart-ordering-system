<?php
include 'db.php';

$res = $conn->query("SELECT * FROM menu");

while($r = $res->fetch_assoc()){

    // Auto update sold out and adjust status
    if($r['available_qty'] <= 0){
        $conn->query("UPDATE menu SET status='Sold Out' WHERE id=".$r['id']);
        $r['status'] = 'Sold Out';
    }

    echo "<div class='food'>";

    echo "<img src='{$r['image']}' alt='{$r['item_name']}'>";
    echo "<h3>{$r['item_name']}</h3>";
    echo "₹{$r['price']}<br>";
    echo "Available: {$r['available_qty']}<br>";
    echo "Status: <b>{$r['status']}</b><br>";
    echo "Waiting Time: <span id='wait_{$r['id']}'>{$r['wait_time']}</span> mins<br><br>";
    // warn low stock when 5 or less and not sold out
    if($r['available_qty'] > 0 && $r['available_qty'] <= 5){
        echo "<span style='color:orange;font-weight:bold;'>Low stock - order soon!</span><br><br>";
    }

    if($r['status'] == 'Sold Out'){
        echo "<b style='color:red;font-size:18px;'>SOLD OUT</b>";
    } else {
?>
<form action="order.php" method="post">
<input type="hidden" name="id" value="<?php echo $r['id']; ?>">

Qty:
<input type="number" name="qty" min="1" max="<?php echo $r['available_qty']; ?>" required>
<br><br>

<select name="mode">
<option value="Online">Online</option>
<option value="Offline">Offline</option>
</select>
<br><br>

<button type="submit">Order</button>
</form>
<?php
    }

    echo "</div>";
}
?>
