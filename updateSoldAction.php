<?php
include 'Include/connection.php';

if(isset($_POST['id'])){

  $id = mysqli_real_escape_string($conn, $_POST['id']);
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $code = mysqli_real_escape_string($conn, $_POST['code']);
  $price = mysqli_real_escape_string($conn, $_POST['price']);
  $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
  $date = mysqli_real_escape_string($conn, $_POST['date']);



  //  query to update data 
   
  $result = mysqli_query($conn, "UPDATE invsold SET price='$price', 
                                                           quantity='$quantity', 
                                                           name = '$name', 
                                                           code =  '$code',
                                                           soldDate =  '$date'
                                                            WHERE sold_id ='$id'");

}
?>