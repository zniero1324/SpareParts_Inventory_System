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

$seller_name = $_SESSION['user']['username'];

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="css/modal.css">
  <link rel="stylesheet" type="text/css" href="css/custom.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min2.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="css/navigation.css">
  <link rel="stylesheet" type="text/css" href="dataTables/dataTables.css">
  <link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap.min.css" />
  <title>Dashboard</title>
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
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-body my-panel pad2">
             <?php
                    $query ="SELECT count(code) as code_quantity FROM invquantity";
                    $result = mysqli_query($conn, $query);
                    while($row = mysqli_fetch_array($result))
                          {
                                echo 'Total Item: '.$row["code_quantity"].'';
               }?>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-body my-panel pad2">
              <?php
                    $query ="SELECT sum(price) as price FROM invquantity";
                    $result = mysqli_query($conn, $query);
                    while($row = mysqli_fetch_array($result))
                          {
                                echo 'Trans. Price: '.$row["price"].'';
               }?>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-body my-panel pad2">
               <?php
                    $query ="SELECT sum(quantity*price) as total_cost FROM invquantity";
                    $result = mysqli_query($conn, $query);
                    while($row = mysqli_fetch_array($result))
                          {
                                echo 'Recievables: '.$row["total_cost"].'';
               }?>
            </div>
          </div>
        </div>
      </div>
    <?php if($_SESSION['user']['designation'] == 'Admin'){ ?>
      <!--This is the start of Input where you will add Items-->
      <div class="row">
        <div class="add_item">
          <div class="row">
            <div class="col-md-6">
              <h4>Add new stock</h4>
            </div>
            <div class="col-md-6">
              <form method="POST" action="import.php">
                <input type="submit" name="export" class="btn btn-success px-5 pull-right" value="Import File">
              </form>
            </div>
          </div>
          <form  method="POST" action="ItemAdd.php">
            
            <div class="row">

              <!--Name of the Item-->
              <div class="col-md-4">
                  <div class="form-group">
                    <label>Name:</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Name">
                  </div>
              </div>

              <!--Code of the Item-->
              <div class="col-md-4">
                  <div class="form-group">
                    <label>Code:</label>
                    <input type="text" class="form-control" name="code" id="code" placeholder="Code">
                  </div>
              </div>

              <!--Cost of the Item-->
              <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Cost:</label>
                    <input type="number" class="form-control" name="cost" id="cost" value=0>
                  </div>
              </div>
              </div>
              

                <div class="row">
                  
                  <div class="form-group col-md-4">
                    <label for="exampleInputPassword1">Price: </label>
                    <input type="number" class="form-control" name="price" id="price" value=0>
                  </div>

                  <div class="form-group col-md-4">
                    <label for="exampleInputPassword1">Quantity: </label>
                    <input type="number" class="form-control" name="quantity" id="quantity" value=0>
                  </div>

                  <div class="form-group col-md-4">
                      <label for="exampleInputPassword1">Seller: </label>
                      <select class="form-control" name="seller" id="seller">
                      <?php

                          $query_username ="SELECT username FROM users";
                                                
                          $result_username = mysqli_query($conn, $query_username);
                          while($row = mysqli_fetch_array($result_username))
                          {
                            $seller = htmlspecialchars($row["username"]);
                            
                            echo'<option value="'.$seller.'">'.$seller.'</option>';
                          }
                      ?>
                      </select>
                  </div>


                </div>
                <div class="row pad2">
                  <input type="submit" id="add" name="add" style="font-weight: bold;font-size: 2rem;" class="btn btn-100 pull-right" value="+ADD+">
                </div>
              </form>
          </div>
            
          <?php }?><!--Endo of if for adding item-->


          <!--This is the table of all your inventories-->
            <div class="table-responsive">
              <div class="row pad2">
                <form method="POST" action="excel.php">
                  <input type="submit" name="export" class="btn btn-success px-5 pull-right" value="Export">
                </form>
              </div>
                  <table id="table_data" name="table_id" class="table" >  
                    <thead>  
                         <tr class="my-bg text-white">
                            <th class="my-bg">CODE</th>
                            <th class="my-bg">NAME</th>
                            
                            <?php if($_SESSION['user']['designation'] == 'Admin'){ 
                              echo '<th class="my-bg">COST</th>';
                              }elseif($_SESSION['user']['designation'] == 'Employee'){
                                echo '<th style="display:none" class="my-bg">Dummy</th>';
                              }?>
                            
                            <th class="my-bg">T. PRICE</th>


                            <?php if($_SESSION['user']['designation'] == 'Admin'){ 
                              echo '<th class="my-bg">PROFIT</th>';
                              }?>
                            <th class="my-bg">QUANTITY</th>
                            <th class="my-bg">TOTAL PRICE</th>

                            <?php if($_SESSION['user']['designation'] == 'Admin'){ 
                              echo '<th class="my-bg">SELLER</th>';
                              }?>

                            <th class="my-bg">ACTION</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php
                      if($_SESSION['user']['designation'] == 'Admin'){
                       $query ="SELECT 
                       invname.code, cost, 
                       code_id, 
                       name, 
                       price, 
                       quantity, 
                       invquantity.username, 
                       date 
                       FROM invname inner join invquantity 
                       on invname.code = invquantity.code 
                       order by code_id ASC;";

                      }elseif($_SESSION['user']['designation'] == 'Employee'){
                        $query ="SELECT 
                        invname.code, cost, 
                        code_id, 
                        name, 
                        price, 
                        quantity, 
                        invquantity.username, 
                        date 
                        FROM invname inner join invquantity 
                        on invname.code = invquantity.code
                        where invquantity.username = '".$seller_name."'
                        order by code_id ASC;";

                      }

                       
                       $result = mysqli_query($conn, $query);
                       while($row = mysqli_fetch_array($result))
                        {
                            $id = htmlspecialchars($row["code_id"]);
                            $code = htmlspecialchars($row["code"]);
                            $name = htmlspecialchars($row["name"]);
                            $price = htmlspecialchars($row["price"]);
                            $cost = htmlspecialchars($row["cost"]);
                            $quantity = htmlspecialchars($row["quantity"]);
                            $Total = number_format($quantity*$price);
                            $profit = number_format($price - $cost);
                            $seller = htmlspecialchars($row["username"]);

                               
                          echo '
                                <tr id="'.$id.'">  
                                    <td data-target="item_code2">'.$code.'</td>
                                    <td data-target="item_name2">'.$name.'</td>';

                                if($_SESSION['user']['designation'] == 'Admin'){ 
                          echo '
                                  <td data-target="item_cost2">'.$cost.'</td>';
                                  } elseif($_SESSION['user']['designation'] == 'Employee'){
                                    echo '<td style="display:none;" data-target="item_cost2">'.$cost.'</td>';
                                  }
                          echo '  <td data-target="item_price2">'.$price.'</td>';
                                if($_SESSION['user']['designation'] == 'Admin'){ 
                          echo '
                                  <td data-target="item_profit">'.$profit.'</td>';
                                  }
                                    
                            echo '<td data-target="item_quantity2">'.$quantity.'</td>
                                    <td data-target="item_total">'.$Total.'</td>';

                                    if($_SESSION['user']['designation'] == 'Admin'){ 
                                    
                                      echo '
                                      <td>'.$seller.'</td>
                                      <td> 
                                        <ul style="padding: 0;">
                                              <li class="dropdown">
                                                <button href="#" data-toggle="dropdown" class="btn btn-warning btn-secondary dropdown-toggle">Action
                                                <span class="caret"></span></b></button>
                                                <ul class="dropdown-menu" style="min-width: 0rem;">
                                                  <li><a href="#" data-role="selling"  data-id="'.$id.'">Sell</a></li>
                                                  <li><a href="#" data-role="update"  data-id="'.$id.'" >Edit</a></li>
                                                  <li><a href=ItemDelete.php?id='.$id.' >Delete</a></li>
                                                </ul>
                                              </li>
                                              </ul>';
                                            }else {
                                            echo '<td><a href="#" data-role="selling" class="btn btn-warning"  data-id="'.$id.'">Sell</a>';
                                          }

                                    echo'</td>
                                  </tr>';
                             }

                                ?>
                        </tbody> 
                   </table>
                </div>
              </div>
                    <div id="mod" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                          <!-- Modal content-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">EDIT DATA</h4>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                              <div class="modal-body">
                                 <label>Name:</label>
                                 <input type="text" class="form-control" name="item_name2" id="item_name2">
                              </div>
                              <div class="modal-body">
                                 <label>Cost:</label>
                                 <input type="number" class="form-control" name="item_cost2" id="item_cost2">
                              </div>
                              <div class="modal-body">
                                   <label>Quantity:</label>
                                   <input type="number" class="form-control" name="item_quantity2" id="item_quantity2">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="modal-body">
                                   <label>Code:</label>
                                   <input type="text" class="form-control" name="item_code2" id="item_code2">
                                </div>
                                <div class="modal-body">
                                   <label>Price:</label>
                                   <input type="number" class="form-control" name="item_price2" id="item_price2">
                                </div>
                              </div>
                            </div>
                            <input type="hidden" id="code_id" class="form-control">
                            <div class="modal-footer">
                              <a href="#" id="confirm" name="confirm" class="btn btn-primary pull-right">Confirm</a>
                              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            </div>
                          </div>

                        </div>
                      </div> 
                  
                      <!--Sell Modal-->  
                      <div id="sellModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                          <!-- Modal content-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Sell Quantity</h4>
                            </div>
                            <div class="modal-body">
                               <label>How many do you wish to sell?</label>
                               <input type="number" class="form-control" name="sell_quantity" id="sell_quantity">
                               <input type="hidden" id="sell_name" class="form-control">
                               <input type="hidden" id="sell_code" class="form-control">
                               <input type="hidden" id="sell_stock" class="form-control">
                               <input type="hidden" id="dummy" class="form-control">
                               <input type="hidden" id="sell_price" class="form-control">
                               <input type="hidden" id="sell_id" class="form-control">
                            </div>
                            <div class="modal-body">
                               <label>When did you sell?</label>
                               <input type="date" class="form-control" name="sell_date" id="sell_date">
                            </div>
                            <div class="modal-footer">
                              <a href="#" id="sell" name="sell" class="btn btn-primary pull-right">Confirm</a>
                              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                            </div>
                          </div>

                        </div>
                      </div>            
          </div>
        </div>
        <button onclick="topFunction()" id="myBtn" class="myBtn" title="Go to top"><img src="img/ascending.png"></button>

        <script src="js/ajax.js"></script> 
        <script src="js/custom.js"></script> 
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="dataTables/dataTables.js"></script>  
        <script src="js/dataTables.bootstrap.min.js"></script> 

            <script>
                  $(document).ready(function(){
                      $('#table_data').DataTable({

                          responsive: true,


                      });

                      $(document).on('click','a[data-role=update]',function(){
                        var id = ($(this).data('id'));
                        var code = $('#'+id).children('td[data-target=item_code2]').text();
                        var cost = $('#'+id).children('td[data-target=item_cost2]').text();
                        var name  = $('#'+id).children('td[data-target=item_name2]').text();
                        var price  = $('#'+id).children('td[data-target=item_price2]').text();
                        var quantity  = $('#'+id).children('td[data-target=item_quantity2]').text();



                        $('#item_code2').val(code);
                        $('#item_cost2').val(cost);
                        $('#code_id').val(id);
                        $('#item_name2').val(name);
                        $('#item_price2').val(price);
                        $('#item_quantity2').val(quantity);
                        $('#mod').modal('toggle');


                      });
                     $(document).on('click','a[data-role=selling]',function(){
                        var id = ($(this).data('id'));
                        var code = $('#'+id).children('td[data-target=item_code2]').text();
                        var name  = $('#'+id).children('td[data-target=item_name2]').text();
                        var price  = $('#'+id).children('td[data-target=item_price2]').text();
                        var quantity  = $('#'+id).children('td[data-target=item_quantity2]').text();
                        var cost  = $('#'+id).children('td[data-target=item_cost2]').text();

                        
                        $('#sell_id').val(id);
                        $('#dummy').val(cost);
                        $('#sell_code').val(code);
                        $('#sell_name').val(name);
                        $('#sell_price').val(price);
                        $('#sell_quantity').val(quantity);
                        $('#sell_stock').val(quantity);
                        $('#sellModal').modal('toggle');

                      });

                      $('#confirm').click(function(){
                        var id = $('#code_id').val();
                        var code = $('#item_code2').val();
                        var name = $('#item_name2').val();
                        var price = $('#item_price2').val();
                        var cost = $('#item_cost2').val();
                        var quantity = $('#item_quantity2').val();
                        var total = price * quantity;
                        var profit = price - cost;


                          $.ajax({
                              url      : 'update.php',
                              method   : 'post',  
                              data     : {id: id, cost:cost, code:code, name:name, price:price, quantity:quantity},

                              success  : function(response){
                                            // now update user record in table 
                                             $('#'+id).children('td[data-target=item_profit]').text(profit);
                                             $('#'+id).children('td[data-target=item_total]').text(total);
                                             $('#'+id).children('td[data-target=item_code2]').text(code);
                                             $('#'+id).children('td[data-target=item_name2]').text(name);
                                             $('#'+id).children('td[data-target=item_cost2]').text(cost);
                                             $('#'+id).children('td[data-target=item_price2]').text(price);
                                             $('#'+id).children('td[data-target=item_quantity2]').text(quantity);
                                             $('#mod').modal('toggle'); 
                                             
                                         }
                          });
                       });

                    $('#sell').click(function(){
                        var cost = '0';
                        var id = $('#sell_id').val();
                        var code = $('#sell_code').val();
                        var name = $('#sell_name').val();
                        var price = $('#sell_price').val();
                        var stock = $('#sell_stock').val();
                        var cost = $('#dummy').val();
                        var date = $('#sell_date').val();
                        var quantity = $('#sell_quantity').val();

                          $.ajax({
                              url      : 'ItemSell.php',
                              method   : 'post',  
                              data     : {code:code, name:name, price:price, quantity:quantity, stock:stock, date:date, cost:cost},

                              success  : function(response){

                                        if(($('#sell_quantity').val() - $('#sell_stock').val()) > '0') {
                                          alert("Insufficient stock to sell");
                                        
                                        }else{

                                          $('#'+id).children('td[data-target=item_quantity2]').text(stock-quantity);
                                          $('#sellModal').modal('toggle');
                                        
                                        } 
                                                
                            }
                          });
                       });
                  });
          </script> 

</body>
</html>