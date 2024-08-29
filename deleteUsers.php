<?php
include 'Include/connection.php';

if(isset($_GET['userID'])){
    $userID =  $_GET['userID'];

    $result  = mysqli_query($conn, "DELETE FROM users WHERE employee_id='$userID'");
    header( "Location:Users.php" );
}

?>