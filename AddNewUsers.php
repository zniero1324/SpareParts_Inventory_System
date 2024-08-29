<?php
include 'Include/connection.php';

if(isset($_POST['username'])){
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $designation = mysqli_real_escape_string($conn, $_POST['roles']);



  //  query to update data 
   
  $result  = mysqli_query($conn, "INSERT INTO `users` (`employee_id`, `username`, `password`, `designation`) VALUES (NULL, '$username', '$password', '$designation')");

}

?>