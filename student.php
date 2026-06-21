<?php 
include 'db.php';

if(!isset($_SESSION['student_id'])){
    header("Location: studentLogin.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'];

// determine personalized recommendation (most ordered item)
$rec_item = null;
$rec_res = $conn->query("SELECT m.*, SUM(o.qty) AS total_qty
    FROM orders o
    JOIN menu m ON o.item_id=m.id
    WHERE o.student_id=$student_id
    GROUP BY o.item_id
    ORDER BY total_qty DESC
    LIMIT 1");
if($rec_res && $rec_res->num_rows > 0){
    $rec_item = $rec_res->fetch_assoc();
}

// determine global popular item (highest total qty)
$glob_item = null;
$glob_res = $conn->query("SELECT m.*, SUM(o.qty) AS total_qty
    FROM orders o
    JOIN menu m ON o.item_id=m.id
    GROUP BY o.item_id
    ORDER BY total_qty DESC
    LIMIT 1");
if($glob_res && $glob_res->num_rows > 0){
    $glob_item = $glob_res->fetch_assoc();
}
?>

<link rel="stylesheet" href="style.css">

<h1 align="center">Food Menu</h1>

<?php if($glob_item){ ?>
<div style="width:80%; margin:auto; background:#e3f2fd; padding:15px; border:2px solid #2196f3; border-radius:10px;">
    <h2>Trending Now:</h2>
    <div class="food" style="display:inline-block;">
        <img src="<?php echo $glob_item['image']; ?>" alt="<?php echo htmlspecialchars($glob_item['item_name']); ?>" style="width:120px;height:120px;object-fit:cover;">
        <h3><?php echo htmlspecialchars($glob_item['item_name']); ?></h3>
        Price: ₹<?php echo $glob_item['price']; ?><br>
        <?php if($glob_item['status'] != 'Sold Out'){ ?>
            <a href="student.php#menu"><button style="margin-top:5px;">View on Menu</button></a>
        <?php } else { ?>
            <span style="color:red;font-weight:bold;">Currently unavailable</span>
        <?php } ?>
    </div>
</div>
<br>
<?php } ?>

<?php if($rec_item){ ?>
<div style="width:80%; margin:auto; background:#fdfde3; padding:15px; border:2px solid #f1c40f; border-radius:10px;">
    <h2>Recommended for you, <?php echo htmlspecialchars($student_name); ?>:</h2>
    <div class="food" style="display:inline-block;">
        <img src="<?php echo $rec_item['image']; ?>" alt="<?php echo htmlspecialchars($rec_item['item_name']); ?>" style="width:120px;height:120px;object-fit:cover;">
        <h3><?php echo htmlspecialchars($rec_item['item_name']); ?></h3>
        Price: ₹<?php echo $rec_item['price']; ?><br>
        <?php if($rec_item['status'] != 'Sold Out'){ ?>
            <a href="student.php#menu"><button style="margin-top:5px;">View on Menu</button></a>
        <?php } else { ?>
            <span style="color:red;font-weight:bold;">Currently unavailable</span>
        <?php } ?>
    </div>
</div>
<br>
<?php } ?>

<!-- Logout Button -->
<div align="right" style="margin-right:20px;">
    <a href="logout.php">
        <button>Logout</button>
    </a>
</div>

<?php
// Crowd Level
$crowd_data = $conn->query("SELECT crowd_level FROM admin")->fetch_assoc();
$crowd = $crowd_data['crowd_level'];

echo "<h3 align='center'>Crowd Level: <b>$crowd</b></h3>";
?>

<!-- MENU SECTION -->
<div id="menu"></div>

<script>
function loadMenu(){
    fetch("menuAPI.php")
    .then(res => res.text())
    .then(data => document.getElementById("menu").innerHTML = data);
}

// only fetch menu once on first load
loadMenu();
// update wait times shortly after menu renders
setTimeout(updateWaitTimes, 500);

// smart waiting time update: hit endpoint periodically and update spans
function updateWaitTimes(){
    fetch('waitTimeAPI.php')
    .then(res => res.json())
    .then(obj => {
        for(let id in obj){
            let span = document.getElementById('wait_'+id);
            if(span){
                span.textContent = obj[id] + ' mins';
            }
        }
    })
    .catch(err=>console.error('waitTime error',err));
}
// refresh every 15 seconds
setInterval(updateWaitTimes,15000);
</script>

<hr>

<h2 align="center">My Orders</h2>

<div id="orders"></div>

<script>
function loadOrders(){
    fetch("myOrdersAPI.php")
    .then(res => res.text())
    .then(data => document.getElementById("orders").innerHTML = data);
}

// Refresh only orders every 3 seconds
setInterval(loadOrders, 3000);
loadOrders();
</script>


