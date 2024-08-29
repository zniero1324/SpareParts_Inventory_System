<?php
session_start();
include 'Include/connection.php';
include 'Include/navigation.php';


//Checking User Logged or Not
if(empty($_SESSION['user'])){
    header('location:index.php');
}


//timeout after 5 sec
if(isset($_SESSION['user'])) {
    if((time() - $_SESSION['last_time']) > 1800) {
      header("location:logout.php");  
    }
}


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="css/modal.css">
  <link rel="stylesheet" type="text/css" href="css/custom.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min2.css">
  <link rel="stylesheet" type="text/css" href="css/navigation.css">
  <link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap.min.css" /> 
  <title>List of Sold Item</title>
</head>
<body>
<div class="container-fluid">
    <nav id="myNavbar" class="navbar nav-color" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="dashboard.php"><img style="width: 80px;" src="img/LoginLogo.jpg"></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <?php
                    echo navigate_it()
                ?>
                <ul class="nav navbar-nav navbar-right">
                <?php
                  echo navigate_right();

                  ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3">
              <div class="panel panel-default">
                <div class="panel-body my-panel pad2">
                 <?php
                        $query ="SELECT count(sold_id) as item_sold FROM invsold";
                        $result = mysqli_query($conn, $query);
                        while($row = mysqli_fetch_array($result))
                              {
                                    echo 'Total Sold Item: '.$row["item_sold"].'';
                   }?>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="panel panel-default">
                <div class="panel-body my-panel pad2">
                  <?php
                        $query ="SELECT sum(price) as price FROM invsold";
                        $result = mysqli_query($conn, $query);
                        while($row = mysqli_fetch_array($result))
                              {
                                    echo 'Total Transfer Price: '.$row["price"].'';
                   }?>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="panel panel-default">
                <div class="panel-body my-panel pad2">
                   <?php
                        $query ="SELECT sum(quantity*price) as total_cost FROM invsold";
                        $result = mysqli_query($conn, $query);
                        while($row = mysqli_fetch_array($result))
                              {
                                    echo 'Total Price Sold: '.$row["total_cost"].'';
                    }?>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="panel panel-default">
                <div class="panel-body my-panel pad2">
                  <?php
                        $query ="SELECT sum(price-cost) as profit FROM invsold";
                        $result = mysqli_query($conn, $query);
                        while($row = mysqli_fetch_array($result))
                              {
                                if($_SESSION['user']['designation'] == 'Admin'){
                                     echo 'Total Margin: '.$row["profit"].'';
                                    }
                   }?>
                </div>
              </div>
            </div>
          </div>
        <div class="row">
            <div class="row">
              <a href="#" data-role="extract_reports" class="btn btn-success pull-right">Export</a>  
            </div>
          <table id="table_data" name="table_id" class="table" >  
            <thead>  
                 <tr class="my-bg text-white">
                    <th class="my-bg">Item Code</th>
                    <th class="my-bg">Item Name</th>
                    <?php if($_SESSION['user']['designation'] == 'Admin'){ 
                                echo '<th class="my-bg">Item Cost</th>';
                              }?>
                    <th class="my-bg">Transfer Price</th>
                    <?php if($_SESSION['user']['designation'] == 'Admin'){ 
                                echo '<th class="my-bg">Item Profit</th>';
                            } ?>
                    <th class="my-bg">Item Quantity</th>
                    <th class="my-bg">Total Price</th>
                    <th class="my-bg">Date Sold</th>
                    <?php if($_SESSION['user']['designation'] == 'Admin'){ 
                                echo '<th class="my-bg">Action</th>';
                              }?>
                 </tr>
              </thead>
              <tbody>
            <?php
               $query ="SELECT code, cost, sold_id, name, price, quantity, soldDate FROM invsold;";
               
               $result = mysqli_query($conn, $query);
               while($row = mysqli_fetch_array($result))
                {
                    $id = htmlspecialchars($row["sold_id"]);
                    $code = htmlspecialchars($row["code"]);
                    $name = htmlspecialchars($row["name"]);
                    $price = htmlspecialchars($row["price"]);
                    $quantity = htmlspecialchars($row["quantity"]);
                    $curdate = htmlspecialchars($row["soldDate"]);
                    $cost = htmlspecialchars($row["cost"]);
                    $profit = number_format($price-$cost);
                    $Total = number_format($quantity*$price);

                       
                  echo '
                        <tr id="'.$id.'">  
                            <td data-target="code">'.$code.'</td>
                            <td data-target="name">'.$name.'</td>';
                            if($_SESSION['user']['designation'] == 'Admin'){ 
                            echo'<td data-target="cost">'.$cost.'</td>';
                            }
                   echo    '<td data-target="price">'.$price.'</td>';
                    if($_SESSION['user']['designation'] == 'Admin'){ 
                    echo   '<td data-target="item_profit">'.$profit.'</td>';
                                  }
                    echo   '<td data-target="quantity">'.$quantity.'</td>
                            <td data-target="total">'.$Total.'</td>
                            <td data-target="item_date">'.$curdate.'</td>';
                            if($_SESSION['user']['designation'] == 'Admin'){ 
                    echo  '<td><a href="#" data-role="update"  data-id="'.$id.'" class="btn btn-primary">Edit</a>
                                <a href=ItemDelete.php?idSold='.$id.' class="btn btn-danger">Delete</a></td>';
                                }
                    echo     '</tr>';
                     }

    	                    ?>
    	            </tbody> 
    	       </table>
           </div>
	   </div>
	</div>
    <div id="EditSold" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Edit Data</h4>
            </div>
            <div class="modal-body">
               <label>Item Name</label>
               <input type="text" class="form-control" name="name" id="name">
            </div>
            <div class="modal-body">
               <label>Item Code</label>
               <input type="text" class="form-control" name="code" id="code">
            </div>
            <div class="modal-body">
               <label>Item Price</label>
               <input type="number" class="form-control" name="price" id="price">
            </div>
            <div class="modal-body">
               <label>Item Quantity</label>
               <input type="number" class="form-control" name="quantity" id="quantity">
            </div>
            <div class="modal-body">
               <label>Date</label>
               <input type="date" class="form-control" name="date" id="date">
            </div>
            <input type="hidden" id="sold_id" class="form-control">
            <div class="modal-footer">
              <a href="#" id="confirm" name="confirm" class="btn btn-primary pull-right">Confirm</a>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
      </div>

        <div id="exportMod" name="exportMod" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <form method="POST" action="excel.php">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Reports</h4>
                </div>
                <div class="modal-body">
                   <label>Start Date:</label>
                   <input type="date" class="form-control" name="start_date" id="start_date">
                </div>
                <div class="modal-body">
                   <label>End Date:</label>
                   <input type="date" class="form-control" name="end_date" id="end_date">
                </div>
                <div class="modal-footer">
                  <input type="submit" name="ext" class="btn btn-default pull-left" value="Confirm">
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
              </div>
              </form>
            </div>
          </div> 
        <script src="js/ajax.js"></script> 
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>  
        <script src="js/dataTables.bootstrap.min.js"></script>
        <script>
                  $(document).ready(function(){
                      $('#table_data').DataTable();

                      $(document).on('click','a[data-role=update]',function(){
                        var id = ($(this).data('id'));
                        var code = $('#'+id).children('td[data-target=code]').text();
                        var name  = $('#'+id).children('td[data-target=name]').text();
                        var price  = $('#'+id).children('td[data-target=price]').text();
                        var date  = $('#'+id).children('td[data-target=item_date]').text();
                        var quantity  = $('#'+id).children('td[data-target=quantity]').text();



                        $('#code').val(code);
                        $('#sold_id').val(id);
                        $('#name').val(name);
                        $('#price').val(price);
                        $('#date').val(date);
                        $('#quantity').val(quantity);
                        $('#EditSold').modal('toggle');


                      });

                      $(document).on('click','a[data-role=extract_reports]',function(){
                        $('#exportMod').modal('toggle');
                      });

                      $('#confirm').click(function(){
                        var id = $('#sold_id').val();
                        var code = $('#code').val();
                        var name = $('#name').val();
                        var price = $('#price').val();
                        var quantity = $('#quantity').val();
                        var date = $('#date').val();
                        var total = price * quantity;


                          $.ajax({
                              url      : 'updateSoldAction.php',
                              method   : 'post',  
                              data     : {id: id, code:code, name:name, price:price, quantity:quantity, date:date},

                              success  : function(response){
                                            // now update user record in table 
                                            $('#'+id).children('td[data-target=total]').text(total);
                                             $('#'+id).children('td[data-target=code]').text(code);
                                             $('#'+id).children('td[data-target=name]').text(name);
                                             $('#'+id).children('td[data-target=price]').text(price);
                                             $('#'+id).children('td[data-target=quantity]').text(quantity);
                                             $('#'+id).children('td[data-target=item_date]').text(date);
                                             $('#EditSold').modal('toggle'); 
                                             
                                         }
                          });
                       });

                  });
          </script>
</body>
</html>