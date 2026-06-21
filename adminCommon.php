<?php
include 'db.php';

// handle login if posted
if(isset($_POST['pass'])){
    $res = $conn->query("SELECT * FROM admin WHERE password='".$_POST['pass']."'");
    if($res->num_rows == 0){
        die("Wrong Password");
    }
    $_SESSION['admin'] = "yes";
}

// redirect if not authenticated
if(!isset($_SESSION['admin'])){
    header("Location: adminLogin.php");
    exit();
}

// automatic crowd level adjustment based on pending orders
$pending = $conn->query("SELECT COUNT(*) AS cnt FROM orders WHERE order_status IN ('Preparing','Ready')")->fetch_assoc()['cnt'];
$autoCrowd = 'Free';
if($pending > 20) {
    $autoCrowd = 'High';
} elseif($pending > 10) {
    $autoCrowd = 'Medium';
}
$conn->query("UPDATE admin SET crowd_level='$autoCrowd'");
?>