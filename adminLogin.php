<?php
include 'db.php';

if(isset($_POST['pass'])){
    $pass = $_POST['pass'];

    $res = $conn->query("SELECT * FROM admin WHERE password='$pass'");

    if($res->num_rows > 0){
        $_SESSION['admin'] = "yes";
        header("Location: admin.php");
        exit();
    } else {
        echo "<script>alert('Wrong Password');</script>";
    }
}
?>

<link rel="stylesheet" href="style.css">
<div class="center">
<form method="post" class="box">
<h2>Admin Login</h2>
<input type="password" name="pass" placeholder="Enter Password" required><br><br>
<button type="submit">Login</button>
</form>
</div>
