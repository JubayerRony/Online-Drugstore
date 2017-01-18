<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>

    
    <!-- <link rel="stylesheet" href="css/styles.css"> -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
</head>
<body>

<script type="text/javascript" src="bootstrap/js/jquery.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>


<?php

error_reporting(~E_NOTICE);
session_start();
session_regenerate_id(true);

include_once "controllers/userController.php";
include_once "controllers/productController.php";

if($_SESSION['isUser'] == false)
{
    header("Location: index.php");
}



$cartItems = 0;

if($_SESSION['isUser'] == true)
{
    $data = $productController->getTotalItemsAndPrice($_SESSION['uID']);
    $cartItems = $data['items'];
}


if($_POST['submit'])
{   
    
    if($productController->removeProduct($_POST['pID']) == "Successful")
    {
        ?>
        <script type="text/javascript">
        alert("Product successfully removed.");
        </script>
        <?php
    }

    else
    {
        ?>
        <script type="text/javascript">
        alert("Error");
        </script>
        <?php
    }   

            
    
} 



?>

<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <h1><a class="navbar-brand" href="index.php">Online<myTag style="color: #2A5BBB;">DrugStore</myTag></a></h1>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-left">
        <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
        <li><a href="allProducts.php"><span class="glyphicon glyphicon-list"></span> All Products</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="glyphicon glyphicon-phone-alt"></span> Contact Us<span class="caret"></span></a>
          <ul class="dropdown-menu">            
            <li><p style="margin-left: 15px">Hotline: 01XXXXXXX</p></li>
            <li role="separator" class="divider"></li>
            <li><p style="margin-left: 15px">E-mail: help@drugstore.com</p></li>
          </ul>
        </li>
      </ul>
      <form class="navbar-form navbar-right" role="search" style="line-height: 50px;" action="search.php" method="get">
        <div class="form-group" >           
            <input type="text" class="form-control" placeholder="Search for any product..." required name="query">                   
        </div>
        <button type="submit" class="btn btn-default" name="submit" value="Submit"><span class="glyphicon glyphicon-search"></span>Search</button> 
      </form>
      <ul class="nav navbar-nav navbar-right">       
        <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart" style="color: #57C5A0;"><?php echo $cartItems; ?></span> Cart</a></li>
        
        <?php
        if($_SESSION['username'])
        {
        ?>
        <li class="active"><a href=<?php if($_SESSION['isUser'] == true) echo "userProfile.php"; else if($_SESSION['isDoc'] == true) echo "doctorProfile.php"; else echo "adminActions.php"; ?>><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['name']; ?></a></li>

        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>

        <?php
        }

        else
        {
        ?>

        <li class="dropdown">        
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-log-in"></span> Login<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="userLogin.php">As User</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="doctorLogin.php">As Doctor</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="adminLogin.php">As Admin</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="glyphicon glyphicon-share"></span> Sign Up<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="userRegister.php">As User</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="doctorRegister.php">As Doctor</a></li>
          </ul>
        </li>

        <?php
        }
        ?>

      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>


<?php 

if($_SESSION['isUser'] == true)
{  
    $uID = $_SESSION['uID'];

    if($_GET['aID'] && $_GET['sID'])        // User Pressed Cancel Appointment
    {
        $userController->cancelUserAppointment($uID, $_GET['aID'], $_GET['sID']);
    }

    $data = $userController->getUserProfile($uID);

    ?>

        <div class="container" style="margin-top: 150px;">
            <div class="panel panel-default" style="height: atuo;">

                <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <img style="height: 300px; width: 300px; margin: 50 0 60 70" src=<?php echo "\"data:image;base64," . $data['image'] . "\""; ?>>                    
                </div>

                <div class="col-md-6 col-sm-6 col-xs-7">
                    <span class="pull-left" style="margin-top: 50px;">                    
                        <h1 style="margin-bottom: 35px;"><?php echo $data['name']; ?></h1>
                        <h4 style="margin-bottom: 35px;"><strong>Email: </strong> <?php echo $data['email']; ?></h4>
                
                    </span>
                </div>
            </div>
            
        </div>

            
          <?php $data = $userController->getUserAppointments($uID);  ?>
          <h2>My Appointments</h2>                                                                                    
          <div class="table-responsive">          
          <table class="table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Appointment With</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            <?php 
                foreach ($data as $d) 
                {
                    echo "
                    <tr>
                        <td>".$d[0]."</td>
                        <td>".$d[1]."</td>
                        <td>".$d[2]."</td>
                        <td><a href=\"userProfile.php?aID=".$d[3]."&sID=".$d[4]."\" class=\"btn btn-danger\" role=\"button\"><span class=\"glyphicon glyphicon-trash\"></span> Cancel Appointment</a><td>
                    </tr>
                    ";
                }

            ?>      
            </tbody>
          </table>
          </div>
        </div>

        <?php 

}

?>


<footer>
    <div class="container-fluid" style="background: black; color: white; height: 70px; margin-top: 300px;">
        <div class="row">
            <h4 style="line-height: 50px; text-align: center;">Copyright &copy;2016 Abid, Salim, Jubayer</h4>
        </div>
    </div>
</footer>

</body>
</html>