<?php
include 'Include/connection.php';

if(isset($_POST['id'])){
  $id = mysqli_real_escape_string($conn, $_POST['id']);
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $designation = mysqli_real_escape_string($conn, $_POST['designation']);



  //  query to update data 
   
  $result  = mysqli_query($conn, "UPDATE users SET username='$username', password='$password' WHERE employee_id='$id'");

}

?>