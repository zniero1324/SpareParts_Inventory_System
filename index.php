<?php
session_start();
include 'Include/connection.php';

if(isset($_POST['login'])){
    
    $username=mysqli_real_escape_string($conn,$_POST['username']);
    $password=mysqli_real_escape_string($conn,$_POST['password']); 
    $_SESSION['last_time'] = time();
    
        //Checking Login Detail
        $result=mysqli_query($conn,"SELECT * FROM users WHERE username='$username' AND password='$password';");
    
        $row=mysqli_fetch_assoc($result);
        $count=mysqli_num_rows($result);
    
        if($count==1){
            
            $_SESSION['user']=array(
                'username'=>$row['username'],
                'password'=>$row['password'],
                'designation'=>$row['designation']
            );
            
            $designation=$_SESSION['user']['designation'];
            $username=$_SESSION['user']['username'];
            
                    header('location:dashboard.php');
               
        }else{
            $error='Your Username or Password is Incorrect';
        }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="css/customOld.css">
	<title>Login</title>
</head>
<body>
	<div class="container-fluid">
		<div class="container">
			<div class="card card-container">
			    <div class="login-page">

			        <div class="form">
			            <img class="my-img pb-3" src="img/LoginLogo.jpg">
			            <div style="color: red;"><?php if(isset($error)){ echo $error; }?></div>
			            <form class="index.php" method="POST">
			                <input type="text" id="username" name="username" class="form-control" placeholder="username" required>
			                <input  type="password" id="password" name="password" class="form-control" placeholder="Password" required/>
			                <button class="mybtn3" type="submit" name="login">Login</button>
			            </form>
			        </div>
			    </div>
			</div>
		</div>
	</div>
	<div class="footer">
		
	</div>
</body>
</html>