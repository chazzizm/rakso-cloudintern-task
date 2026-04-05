<?php
mysqli_report(MYSQLI_REPORT_OFF); // Turns off detailed database errors

$server = "localhost";
$username = "root";
$password = "";
$database = "keebmods_db"; // We will create this in phpMyAdmin next!

$conn = mysqli_connect($server,$username,$password,$database);

if(!$conn){
    die("<script>alert('connection Failed.')</script>");
}
// else{
//     echo "<script>alert('connection successfully.')</script>";
// }
?>