<?php
//Connect the database
include 'Include/connection.php';

session_start();

if(isset($_POST['code'])){

    if(!$_POST['cost']){
        $cost = '0';
    }else{
        $cost = mysqli_real_escape_string($conn, $_POST['cost']);
    }
    $seller = mysqli_real_escape_string($conn, $_POST['seller']);
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $curdate = date('Y-m-d');

    $allItem = mysqli_query($conn,"SELECT code FROM invname WHERE code = '$code'");

    if(mysqli_num_rows($allItem) >= 1 || $code == '' || $name == '' || $price == '' || $quantity == ''){
        echo "<script>
                alert('Please fill up every details');
                window.location.href='dashboard.php';
            </script>";
    }elseif ($price < 0 || $quantity < 0) {
        echo "<script>
                alert('Nope no negative number');
                window.location.href='dashboard.php';
            </script>";
    }
    else{
    $stmt = $conn->prepare("INSERT INTO invname   
                (code, name) VALUES (?, ?)");
    $stmt->bind_param("ss", $code, $name);
    $stmt->execute();
    $stmt->close();


    $stmt2 = $conn->prepare("INSERT INTO invquantity   
                (code, cost, price, quantity, username, date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("ssssss", $code, $cost, $price, $quantity, $seller, $curdate);
    $stmt2->execute();
    $stmt2->close();

    $conn->close();
    
    echo "<script>
              window.location.href='dashboard.php';
          </script>";

    }
    
    

}
?>
