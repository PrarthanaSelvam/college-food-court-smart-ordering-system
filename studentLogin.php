<?php
include 'db.php';

if(isset($_POST['login'])){

    $name = $_POST['name'];
    $pass = $_POST['pass'];

    $res = $conn->query("SELECT * FROM students WHERE name='$name' AND password='$pass'");

    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        $_SESSION['student_id'] = $row['id'];
        $_SESSION['student_name'] = $row['name'];
        header("Location: student.php");
        exit();
    } else {
        $conn->query("INSERT INTO students(name,password) VALUES('$name','$pass')");
        $_SESSION['student_id'] = $conn->insert_id;
        $_SESSION['student_name'] = $name;
        header("Location: student.php");
        exit();
    }
}
?>

<link rel="stylesheet" href="style.css">
<div class="center">
<form method="post" class="box">
<h2>Student Login</h2>
<input type="text" name="name" placeholder="Name" required><br><br>
<input type="password" name="pass" placeholder="Password" required><br><br>
<button type="submit" name="login">Login</button>
</form>
</div>
