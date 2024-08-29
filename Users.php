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

//Restrict User or Moderator to Access Admin.php page
if($_SESSION['user']['designation']=='Employee'){
    header('location:dashboard.php');
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
  <title>Account Management</title>
</head>
<body>
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
  <div class="container-fluid">
    <div class="container">
      <a type="button" data-role="add" class="btn btn-success pull-right" name="addUser" value="Add a User">Add a User</a>
      <table id="table_data" name="table_id" class="table table-striped table-hover display nowrap" >  
          <thead>  
               <tr class="my-bg text-white">
                  <th class="my-bg">Username</th>
                  <th class="my-bg">Password</th>
                  <th class="my-bg">Designation</th>
                  <th class="my-bg">Action</th>
               </tr>
            </thead>
            <tbody id="table_body">
          <?php

             $query ="SELECT employee_id, username, password, designation FROM users;";
             $result = mysqli_query($conn, $query);

             while($row = mysqli_fetch_array($result))
              {
                  $id = htmlspecialchars($row["employee_id"]);
                  $username = htmlspecialchars($row["username"]);
                  $password = htmlspecialchars($row["password"]);
                  $designation = htmlspecialchars($row["designation"]);
 
                     
                echo '
                        <tr id="'.$id.'">  
                          <td data-target="username">'.$username.'</td>
                          <td data-target="password">'.$password.'</td>
                          <td data-target="designation">'.$designation.'</td>
                          <td><a href="#" data-role="edit" class="btn btn-primary"  data-id="'.$id.'">Edit</a>
                              <a href="deleteUsers.php?userID='.$id.'" class="btn btn-danger">Delete</a>
                          </td>
                        </tr>';
                   }

                      ?>
              </tbody> 
         </table>
        </div>
      </div>
        <div id="editUser" class="modal fade" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Edit Data</h4>
                </div>
                <div class="modal-body">
                   <label>Username</label>
                   <input type="text" class="form-control" name="username" id="username">
                </div>
                <div class="modal-body">
                   <label>Password</label>
                   <input type="text" class="form-control" name="password" id="password">
                </div>
                <input type="hidden" id="employee_id" class="form-control">
                <div class="modal-footer">
                  <a href="#" id="confirm" name="confirm" class="btn btn-primary pull-right">Confirm</a>
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
              </div>

            </div>
          </div>        
        </div>

        <div id="addUser" class="modal fade" role="dialog">
            <div class="modal-dialog">

              <!-- Add new User-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">New User</h4>
                </div>
              <!--New User name-->
                <div class="modal-body">
                   <label>Username</label>
                   <input type="text" class="form-control" name="newUsername" id="newUsername">
                </div>
              
                <!--New Password-->
                <div class="modal-body">
                   <label>Password</label>
                   <input type="text" class="form-control" name="newPassword" id="newPassword">
                </div>

                <!--New Designation-->
                <div class="modal-body">
                   <label>Designation</label>
                   <select class="form-control" name="newRoles" id="newRoles">
                    <option value="Admin">Admin</option>
                    <option value="Employee">Employee</option>
                  </select>
                </div>

                <div class="modal-footer">
                  <a href="#" id="ConfirmNewUser" name="Add user" class="btn btn-success pull-right">Add User</a>
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
              </div>

            </div>
          </div>        
        </div>

        <script src="js/ajax.js"></script> 
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>  
        <script src="js/dataTables.bootstrap.min.js"></script>
        <script>
            $(document).ready(function(){
                $('#table_data').DataTable();

                   $(document).on('click','a[data-role=edit]',function(){
                        var id = ($(this).data('id'));
                        var username = $('#'+id).children('td[data-target=username]').text();
                        var password  = $('#'+id).children('td[data-target=password]').text();


                        $('#employee_id').val(id);
                        $('#username').val(username);
                        $('#password').val(password);
                        $('#editUser').modal('toggle');


                      });

                      $(document).on('click','a[data-role=add]',function(){

                        $('#addUser').modal('toggle');

                      });
                    
                    // Confirm update user information
                   $('#confirm').click(function(){
                        var id = $('#employee_id').val();
                        var username = $('#username').val();
                        var password = $('#password').val();


                          $.ajax({
                              url      : 'UserUpdate.php',
                              method   : 'post',  
                              data     : {id:id, username:username, password:password},

                              success  : function(response){
                                            // now update user record in table 
                                             $('#'+id).children('td[data-target=username]').text(username);
                                             $('#'+id).children('td[data-target=password]').text(password);
                                             $('#editUser').modal('toggle'); 
                                             
                                         }
                          });
                       });

                    // Confirm New user if the id is pressed.
                    $('#ConfirmNewUser').click(function(){
                    var username = $('#newUsername').val();
                    var password = $('#newPassword').val();
                    var roles = $('#newRoles').val();


                      $.ajax({
                          url      : 'AddNewUsers.php',
                          method   : 'post',  
                          data     : {username:username, password:password, roles:roles},

                          success  : function(response){
                                        location.reload();
                                        // Account has been created
                                        $('#addUser').modal('toggle');
                                          
                                      }
                      });
                    });

              });
        </script>
</body>
</html>