<?php
//connetion
$servername="localhost";
$username="root";
$password="";
$database="sign_up";

$conn= mysqli_connect($servername,$username,$password,$database);
if(!$conn)
{
    die("Connection doesn't establish let's try again");
}
else{
    echo"Connected";
}
?>