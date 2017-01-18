<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
</head>
<body>

<script type="text/javascript" src="bootstrap/js/jquery.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>


<?php

error_reporting(~E_NOTICE);
session_start();
session_regenerate_id(true);

include_once "controllers/productController.php";


if(isset($_GET['addToCart']))
{
    if($_SESSION['isUser'] == false)
    {
        ?>
        <script type="text/javascript">
            alert("You need to be logged in as a user to add items to cart.");
        </script>

        <?php 
    }

    else
    {
        if($productController->addProductToCart($_GET['pID'], $_SESSION['uID']) == "Successful")
        {
            ?>
            <script type="text/javascript">
                alert("Product successfully added to cart");
            </script>

            <?php 
        }

        else
        {
            ?>
            <script type="text/javascript">
                alert("Error: Already added to cart/ No such product");
            </script>

            <?php 
        }
    }
}

$cartItems = 0;

if($_SESSION['isUser'] == true)
{
    $data = $productController->getTotalItemsAndPrice($_SESSION['uID']);
    $cartItems = $data['items'];
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
        <li class="active"><a href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
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
        <li><a href=<?php if($_SESSION['isUser'] == true) echo "userProfile.php"; else if($_SESSION['isDoc'] == true) echo "doctorProfile.php"; else echo "adminActions.php"; ?>><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['name']; ?></a></li>

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

<div class="container" >
  <br>
  <div id="myCarousel" class="carousel slide" data-ride="carousel" style="margin-bottom: 35px;">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">

      <div class="item active">
        <img src="images/1.jpg" alt="Chania" width="auto" >
        <div class="carousel-caption">
          <h2 style="color:black; text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;">Need Appointment From A Doctor?</h2>
          <p style="color:black;">Find A Doctor Now!</p>
          <p><a href="availDoctors.php" class="btn btn-success" role="button"><span class="glyphicon glyphicon-info-sign"></span> Available Doctors</a></p>
        </div>
      </div>

      <div class="item">
        <img src="images/2.jpg" alt="Chania" >
        <div class="carousel-caption">
          <h2 style="color:black; text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;">Want Medicines Delivered Straight To Your Home?</h2>
          <p style="color:black;">Browse Our Products And Start Ordering Now!</p>
          <p><a href="allProducts.php" class="btn btn-success" role="button"><span class="glyphicon glyphicon-list"></span> Our Products</a></p>
      </div> 
     
  
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>



<div class="container">
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
            <div class="panel panel-default">
                <div class="panel-body">Latest Products</div>
            </div>

            <?php 

            $data = $productController->getLatesProducts();

            foreach ($data as $d) 
            {
                echo "<div class=\"col-lg-3 col-md-3 col-sm-3 col-xs-3\" style=\"height: 380px; width: 250px; margin: 10px 10px 10px 10px;\">
                <div class=\"thumbnail\" style=\"height: 380px; width: 250px;\">
                    <img src=\"data:image;base64," . $d[2] . "\" style=\"height: 180px; width: 180px;\" alt=\"...\">
                    <div class=\"caption\">
                        <p style=\"text-align: center; font-weight: bold;\">" . $d[0] . "</p>
                        <p style=\"text-align: center;\">";
                        if($_SESSION['isAdmin'] == true)
                        {
                            echo "Product ID: " . $d[3] . "<br>";
                        }

                        echo "Price: ". $d[1]." TK <br>
                        <a href=\"productDetails.php?pID=".$d[3]."\"> Details </a> <br>";
                        if($d[4]) echo "</p><p style=\"text-align: center;\"><a href=\"index.php?addToCart&pID=" . $d[3] . "\" class=\"btn btn-primary\" role=\"button\"><span class=\"glyphicon glyphicon-shopping-cart\" ></span> Add To Cart</a></p>";

                        else echo "</p><p style=\"text-align: center;\"><a href=\"\" class=\"btn btn-danger disabled\" role=\"button\"><span class=\"glyphicon glyphicon-shopping-cart\" ></span> Out Of Stock</a></p>";

                    echo"    
                    </div>
                </div>
            </div>";

            }

            ?>      
            
        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4" >
            <h3 style="text-align: center;">Categories</h3>
            <div class="list-group">
                <a href="category.php?category=1" class="list-group-item" style="font-weight: bold;">Anti-infectives</a>
                <a href="category.php?category=2" class="list-group-item" style="font-weight: bold;">Cough and Cold Relief</a>
                <a href="category.php?category=3" class="list-group-item" style="font-weight: bold;">Diabetes Managements</a>
                <a href="category.php?category=4" class="list-group-item" style="font-weight: bold;">Digestion and Nausea</a>
                <a href="category.php?category=5" class="list-group-item" style="font-weight: bold;">Eye, Nose and Ear Care</a>
                <a href="category.php?category=6" class="list-group-item" style="font-weight: bold;">Oral Care</a>
                <a href="category.php?category=7" class="list-group-item" style="font-weight: bold;">Pain and Fever Relief</a>
                <a href="category.php?category=8" class="list-group-item" style="font-weight: bold;">Respiratory and Cardiovascular</a>
                <a href="category.php?category=9" class="list-group-item" style="font-weight: bold;">Skin Care</a>
                <a href="category.php?category=10" class="list-group-item" style="font-weight: bold;">Vitamins and Minerals</a>
            </div>
        </div>
    </div>  

    <div class="row" style="margin-top: 70px;">
      <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
            <div class="panel panel-default">
                <div class="panel-body">Most Popular Products</div>
            </div>

            <?php 

            $data = $productController->getMostPopularProducts();

            foreach ($data as $d) 
            {
                echo "<div class=\"col-lg-3 col-md-3 col-sm-3 col-xs-3\" style=\"height: 380px; width: 250px; margin: 10px 10px 10px 10px;\">
                <div class=\"thumbnail\" style=\"height: 380px; width: 250px;\">
                    <img src=\"data:image;base64," . $d[2] . "\" style=\"height: 180px; width: 180px;\" alt=\"...\">
                    <div class=\"caption\">
                        <p style=\"text-align: center; font-weight: bold;\">" . $d[0] . "</p>
                        <p style=\"text-align: center;\">";
                        if($_SESSION['isAdmin'] == true)
                        {
                            echo "Product ID: " . $d[3] . "<br>";
                        }

                        echo "Price: ". $d[1]." TK <br>
                        <a href=\"productDetails.php?pID=".$d[3]."\"> Details </a> <br>";
                        if($d[4]) echo "</p><p style=\"text-align: center;\"><a href=\"index.php?addToCart&pID=" . $d[3] . "\" class=\"btn btn-primary\" role=\"button\"><span class=\"glyphicon glyphicon-shopping-cart\" ></span> Add To Cart</a></p>";

                        else echo "</p><p style=\"text-align: center;\"><a href=\"\" class=\"btn btn-danger disabled\" role=\"button\"><span class=\"glyphicon glyphicon-shopping-cart\" ></span> Out Of Stock</a></p>";

                    echo"    
                    </div>
                </div>
            </div>";

            }

            ?>      
            
        </div>
    </div>
</div>



<footer>
    <div class="container-fluid" style="background: black; color: white; height: 70px; margin-top: 200px;">
        <div class="row">
            <h4 style="line-height: 50px; text-align: center;">Copyright &copy;2016 Abid, Salim, Jubayer</h4>
        </div>
    </div>
</footer>

</body>
</html>