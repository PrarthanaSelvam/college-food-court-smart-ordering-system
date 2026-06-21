<?php
include 'db.php';

if(!isset($_SESSION['student_id'])){
    header("Location: studentLogin.php");
    exit();
}

$success = false;

if(isset($_POST['id'])){

    $id = intval($_POST['id']);
    $qty = intval($_POST['qty']);
    $mode = $_POST['mode'];
    $student_id = $_SESSION['student_id'];

    $r = $conn->query("SELECT * FROM menu WHERE id=$id")->fetch_assoc();

    if(!$r){
        die("Food not found");
    }

    if($qty <= 0){
        die("Invalid quantity");
    }

    if($qty > $r['available_qty']){
        die("Not enough stock available");
    }

    $total = $qty * $r['price'];

    /* 🔹 OFFLINE */
    if($mode == "Offline"){

        $conn->query("INSERT INTO orders(student_id,item_id,qty,total,payment_mode)
        VALUES($student_id,$id,$qty,$total,'Offline')");

        // 🔥 Reduce available quantity
        $conn->query("UPDATE menu 
        SET available_qty = available_qty - $qty 
        WHERE id=$id");

        // 🔥 Auto Sold Out if 0
        $conn->query("UPDATE menu 
        SET status='Sold Out' 
        WHERE id=$id AND available_qty <= 0");

        $success = true;
    }

    /* 🔹 ONLINE */
    if($mode == "Online"){

        $_SESSION['item'] = $id;
        $_SESSION['qty'] = $qty;
        $_SESSION['total'] = $total;

        echo "
        <link rel='stylesheet' href='style.css'>
        <div class='center'>
        <div class='box'>
        <h2>Scan QR & Pay ₹$total</h2>
        <img src='qr.png' width='200'><br><br>
        <a href='order.php?confirm=1'>
        <button>Payment Done</button>
        </a>
        </div>
        </div>";
        exit();
    }
}

/* 🔹 After Online Payment Confirm */
if(isset($_GET['confirm'])){

    $id = $_SESSION['item'];
    $qty = $_SESSION['qty'];
    $total = $_SESSION['total'];
    $student_id = $_SESSION['student_id'];

    $conn->query("INSERT INTO orders(student_id,item_id,qty,total,payment_mode)
    VALUES($student_id,$id,$qty,$total,'Online')");

    // 🔥 Reduce quantity
    $conn->query("UPDATE menu 
    SET available_qty = available_qty - $qty 
    WHERE id=$id");

    // 🔥 Auto Sold Out if 0
    $conn->query("UPDATE menu 
    SET status='Sold Out' 
    WHERE id=$id AND available_qty <= 0");

    unset($_SESSION['item']);
    unset($_SESSION['qty']);
    unset($_SESSION['total']);

    $success = true;
}
?>

<link rel="stylesheet" href="style.css">

<div class="center">
<div class="box">

<?php if($success){ ?>

<h2 style="color:green;">Order Placed Successfully ✅</h2>
<br>
<a href="student.php">
<button>Back to Menu</button>
</a>

<?php } ?>

</div>
</div>
