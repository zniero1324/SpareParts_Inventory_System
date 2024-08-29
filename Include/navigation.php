<?php  

function navigate_it() 
 {
    $output="";

    $output.='<ul class="nav navbar-nav">
                    <li><a href="dashboard.php">Inventory</a></li>
                    <li><a href="ItemSoldList.php">Transaction</a></li>
                </ul>';
        
    
    return $output;    
}


 function navigate_right() 
 {
        $output="";
        $output.='  <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle">Setting
                        <span class="caret"></span></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="Users.php">Users Management</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </li>';       


    

    return $output;
}


?>

