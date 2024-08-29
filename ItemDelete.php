<?php 
include 'Include/connection.php';

if(isset($_GET['id'])){

  $id = $_GET['id'];
    
  $query = mysqli_query($conn, "DELETE FROM invname WHERE code_id = '$id'");
  header("location:dashboard.php"); 
}

if(isset($_GET['idSold'])){

  $id = $_GET['idSold'];

  $query = mysqli_query($conn, "DELETE FROM invsold WHERE sold_id = '$id'");
  header("location:ItemSoldList.php"); 
}
?>
