<?php
include 'Include/connection.php';

if(isset($_POST['id'])){
  $cost ='0';

  $id = mysqli_real_escape_string($conn, $_POST['id']);
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $code = mysqli_real_escape_string($conn, $_POST['code']);
  $cost = mysqli_real_escape_string($conn, $_POST['cost']);
  $price = mysqli_real_escape_string($conn, $_POST['price']);
  $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
  $date = date('Y-m-d');


  //  query to update data 
   
  $result  = mysqli_query($conn, "UPDATE invname SET code='$code', name='$name' WHERE code_id='$id'");
  $result2  = mysqli_query($conn, "UPDATE invquantity SET price='$price', quantity='$quantity', cost='$cost', date ='$date' WHERE code ='$code'");

}
?>