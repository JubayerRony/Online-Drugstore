<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>

    
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

include_once "controllers/productController.php";

if($_SESSION['isUser'] == false)
{
    header("Location: index.php");
}

if(isset($_POST['submit']))
{
    $update = $_POST['update'];  
    $updatePID = $_POST['updatePID']; 
    $ret = $productController->updateTheCart($_SESSION['uID'], $update, $updatePID);
}

if(isset($_POST['checkout']))
{
    $data[0] = $_SESSION['uID'];
    $data[1] = $_POST['phone'];
    $data[2] = $_POST['address'];
    $ret = $productController->checkoutCartItems($data);
    if($ret)
    {
        ?>
        <script type="text/javascript">
        alert("Order Successfully Placed");
        </script>
        <?php
    }
    else 
    {
        ?>
        <script type="text/javascript">
        alert("Error: Some of your ordered products may be currently out of stock.");
        </script>
        <?php
    }
}

if(isset($_GET['remove']))
{
    $productController->removeProductFromCart($_GET['pID'], $_SESSION['uID']);
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
        <li class="active"><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart" style="color: #57C5A0;"><?php echo $cartItems; ?></span> Cart</a></li>
        
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





<div class="container" style="margin-top: 135px">
    <div class="panel panel-default">
                <div class="panel-body" style="text-align: center;">SHOPPING CART</div>
    </div>

    <form action="cart.php" method="post" >
        <table id="cart" class="table table-hover table-condensed table-responsive">
            <thead>
                <tr>
                    <th style="width:50%">Product</th>
                    <th style="width:10%">Unit Price</th>
                    <th style="width:8%">Quantity</th>
                    <th style="width:22%" class="text-center">Subtotal</th>
                    <th style="width:10%"></th>
                </tr>
            </thead>

            <tbody>

            <?php                

                $data = $productController->getCartItems($_SESSION['uID']);
                $totalPriceData = $productController->getTotalItemsAndPrice($_SESSION['uID']);

                $i = 0;
                foreach ($data as $d) 
                {                       

                    echo "<tr>
                    <td data-th=\"Product\">
                        <div class=\"row\">
                            <div class=\"col-sm-3\"><img style=\"height:100px;width: 100px;\" src=\"data:image;base64," . $d[4] . "\"  alt=\"...\" class=\"img-responsive\"/></div>
                            <div class=\"col-sm-9\" >
                                <h4 class=\"nomargin\" style=\"line-height:75px;\">". $d[0] ."</h4>
                                
                            </div>
                        </div>
                    </td>
                    <td data-th=\"Price\">".$d[2]." TK</td>
                    <td data-th=\"Quantity\">
                        <input type=\"number\" class=\"form-control text-center\" name=\"update[]\" value=\"".$d[1]."\">
                        <input type=\"hidden\" name=\"updatePID[]\" value=\"".$d[3]."\">";

                    if($ret[$i]>0) echo "Out Of Stock<br>Available: ". $ret[$i];
                    else if($ret[$i] == -1) echo "Out Of Stock<br>Available: 0";
                    echo "</td>
                    <td data-th=\"Subtotal\" class=\"text-center\">". $d[1]*$d[2]." TK</td>
                    <td class=\"actions\" data-th=\"\">
                        
                        <a href=\"cart.php?remove&pID=". $d[3] ."\" class=\"btn btn-danger btn-sm\" role=\"button\"><span class=\"glyphicon glyphicon-trash\"></span></a>
                                                   
                    </td>
                </tr>";

                $i++;
                }

            ?>                

            </tbody>
            <tfoot>                     
                <tr>
                 <?php 
                    if($totalPriceData['items'] > 0) echo"
                    <td><button type=\"submit\" value=\"submit\" name=\"submit\" class=\"btn btn-info\"><span class=\"glyphicon glyphicon-refresh\"></span>Update Cart</button></td>";
                    else echo"
                    <td><button type=\"button\" class=\"btn btn-info disabled\"><span class=\"glyphicon glyphicon-refresh\"></span>Update Cart</button></td>";
                ?>

                    <td colspan="2" class=""></td>
                    <td class="text-center"><strong>Total: <?php echo $totalPriceData['price'] . " TK"; ?></strong></td>
                    <?php 
                    if($totalPriceData['items'] > 0) 
                    echo "<td><button type=\"button\" class=\"btn btn-success\" data-toggle=\"collapse\" data-target=\"#checkout\"><span class=\"glyphicon glyphicon-check\"></span> Checkout</i></button></td>";
                    else
                    echo "<td><button type=\"button\" class=\"btn btn-success disabled\" ><span class=\"glyphicon glyphicon-check\"></span> Checkout</i></button></td>";
                    ?>
                </tr>
            </tfoot>
        </table>
    </form>

    <div id="checkout" class="collapse">

    <section class="login" style="margin-top: 150px">
        <div class="titulo">Checkout</div>
        <form action="cart.php" method="post" enctype="multipart/form-data">            
            <input type="text" required title="Phone"  placeholder="Phone Number"  name="phone">
            <textarea name="address" cols="29" rows="5" placeholder="Delivery Address"></textarea>
            <input type="text" name="method" value="Payment Method: Cash On Delivery" readonly><br>
            
            <button class="enviar" type="submit" value="Submit" name="checkout">Place Order</button> 
        </form>
    </section>


    </div>
</div>


<footer>
    <div class="container-fluid" style="background: black; color: white; height: 70px; margin-top: 400px;">
        <div class="row">
            <h4 style="line-height: 50px; text-align: center;">Copyright &copy;2016 Abid, Salim, Jubayer</h4>
        </div>
    </div>
</footer>

</body>
</html>