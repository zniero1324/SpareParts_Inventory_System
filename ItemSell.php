<?php
include 'Include/connection.php';

if(isset($_POST['code'])){
    
    if(!$_POST['cost']){
        $cost = '0';
    }else{
        $cost = mysqli_real_escape_string($conn, $_POST['cost']);
    }
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $quantityStock = $stock - $quantity;
    $curdate = date('Y-m-d');

    $query = "SELECT quantity FROM invquantity WHERE code = '$code';";
    $result = mysqli_query($conn, $query);
    
    if($quantityStock >= '0'){

    $stmt = $conn->prepare("INSERT INTO invsold  
                (code, cost, name, quantity, price, soldDate) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $code, $cost, $name, $quantity, $price, $curdate);
    $stmt->execute();
    $stmt->close();

    
    $result2  = mysqli_query($conn, "UPDATE invquantity SET quantity='$quantityStock' WHERE code ='$code'");

    }else{
        $conn->close();
    }

}

?>