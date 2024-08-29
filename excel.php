<?php
session_start();
include 'Include/connection.php';
include 'Include/navigation.php';

if(isset($_POST["start_date"]))
 {
 	$output='';
  $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
  $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
  $query ="SELECT code, cost, sold_id, name, price, quantity, soldDate FROM invsold WHERE soldDate >= '$start_date' AND soldDate <= '$end_date';";


	$result = mysqli_query($conn, $query);

	$output .='<table border=1 class="table">  
			        <thead>  
			             <tr>
			                <th>Item Code</th>
			                <th>Item Name</th>';
			            if($_SESSION['user']['designation'] == 'Admin'){ 
			     $output .='<th>Item Cost</th>';
	                     }
			     $output .='<th>Item Price</th>';

                  if($_SESSION['user']['designation'] == 'Admin'){ 
           $output .='<th>Item Profit</th>';
                       }
           $output .='<th>Item Quantity</th>
			                <th>Total Cost</th>
			                <th>Date Sold</th>
			              </tr>
			          </thead>';
		$output .='<tbody>';
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

                   
              $output.= '
                    <tr>  
                        <td>'.$code.'</td>
                        <td>'.$name.'</td>';
                        if($_SESSION['user']['designation'] == 'Admin'){ 
              $output.='<td>'.$cost.'</td>';
                        }
              $output.='<td>'.$price.'</td>';
              if($_SESSION['user']['designation'] == 'Admin'){

                $output.='<td>'.$profit.'</td>';
                        
                        }
              $output.='<td>'.$quantity.'</td>
                        <td>'.$Total.'</td>
                        <td>'.$curdate.'</td>
                     </tr>';
                 }

        $query2 ="SELECT sum(price) as price2, sum(cost) as cost2 FROM invsold WHERE soldDate >= '$start_date' AND soldDate <= '$end_date';";

        $result2 = mysqli_query($conn, $query2);
         while($row2 = mysqli_fetch_array($result2))
            {
              $price2 = htmlspecialchars($row2["price2"]);
              $cost2 = htmlspecialchars($row2["cost2"]);
              $profit2 = $price2 - $cost2;
               if($_SESSION['user']['designation'] == 'Admin'){   
                  $output.='
                              <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td>Total Profit:</td>
                              <td>'.$profit2.'</td>
                              <td></td>
                           </tr>';
                         }
            }      
          $output .='<tbody>
           				</table>';
                  header("Content-Type:application/xls");
                  header("Content-Disposition: attachment; filename=Sold Report.xls");
                  echo $output;

 }

if(isset($_POST["export"]))
{
  $output='';
  $query ="SELECT invname.code, cost, code_id, name, price, quantity FROM invname inner join invquantity on invname.code = invquantity.code";

  $result = mysqli_query($conn, $query);

  $output .='<table border=1 class="table">  
              <thead>  
                   <tr>
                      <th>Item Code</th>
                      <th>Item Name</th>';
                  if($_SESSION['user']['designation'] == 'Admin'){ 
           $output .='<th>Item Cost</th>';
                       }
           $output .='<th>Item Price</th>
                      <th>Item Quantity</th>
                      <th>Total Cost</th>
                    </tr>
                </thead>';
    $output .='<tbody>';
           while($row = mysqli_fetch_array($result))
            {

                $code = htmlspecialchars($row["code"]);
                $name = htmlspecialchars($row["name"]);
                $price = htmlspecialchars($row["price"]);
                $quantity = htmlspecialchars($row["quantity"]);
                $cost = htmlspecialchars($row["cost"]);
                $Total = number_format($quantity*$price);

                   
              $output.= '
                    <tr>  
                        <td>'.$code.'</td>
                        <td>'.$name.'</td>';
                        if($_SESSION['user']['designation'] == 'Admin'){ 
              $output.='<td data-target="cost">'.$cost.'</td>';
                        }
              $output.='<td>'.$price.'</td>
                        <td>'.$quantity.'</td>
                        <td>'.$Total.'</td>
                     </tr>';
                 }

           $output .='<tbody>
                  </table>';
      header("Content-Type:application/vnd.ms-excel");
      header("Content-Disposition: attachment; filename=Inventory Report.xls");
      echo $output;

 }


?>