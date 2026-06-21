<?php
include 'db.php';

if(isset($_GET['id'])){
    $id = intval($_GET['id']);

    // Delete order from database
    $conn->query("DELETE FROM orders WHERE id=$id");
}

header("Location: student.php");
exit();
?>
