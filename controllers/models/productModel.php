<?php 


include_once "myFunctions.php";

session_start();
session_regenerate_id();	

function insertProduct($data)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");


	$name = cleanInput($dbCon, $data['name']);
	$price = cleanInput($dbCon, $data['price']);
	$description = cleanInput($dbCon, $data['description']);
	$category = cleanInput($dbCon, $data['category']);	
	$stock = cleanInput($dbCon, $data['stock']);
	$keywords = cleanInput($dbCon, $data['keywords']);


	$image = addslashes($data['image']);
	$image = file_get_contents($image);	
	$image = base64_encode($image);


	$sql = "INSERT INTO products (name, price, category, description, keywords, stock, soldunits, image) 
			VALUES ('$name', '$price', '$category', '$description', '$keywords', '$stock', '0', '$image')";
	$query = mysqli_query($dbCon, $sql);
	return $query;
}


function allProducts()
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$sql = "SELECT * FROM products ORDER BY name asc";
	$query = mysqli_query($dbCon, $sql);
	$r = 0;

	while($row = mysqli_fetch_assoc($query)) 
	{
		$data[$r][0] = $row['name'];
		$data[$r][1] = $row['price'];
		$data[$r][2] = $row['image'];
		$data[$r][3] = $row['pID'];
		$data[$r][4] = $row['stock'];
		$r++;
	}

	return $data;
}

function latestProducts()
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$sql = "SELECT * FROM products ORDER BY pID desc LIMIT 9";
	$query = mysqli_query($dbCon, $sql);
	$r = 0;

	while($row = mysqli_fetch_assoc($query)) 
	{
		$data[$r][0] = $row['name'];
		$data[$r][1] = $row['price'];
		$data[$r][2] = $row['image'];
		$data[$r][3] = $row['pID'];
		$data[$r][4] = $row['stock'];
		$r++;
	}

	return $data;
}

function mostPopularProducts()
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$sql = "SELECT * FROM products ORDER BY soldunits desc, name asc LIMIT 9";
	$query = mysqli_query($dbCon, $sql);
	$r = 0;

	while($row = mysqli_fetch_assoc($query)) 
	{
		$data[$r][0] = $row['name'];
		$data[$r][1] = $row['price'];
		$data[$r][2] = $row['image'];
		$data[$r][3] = $row['pID'];
		$data[$r][4] = $row['stock'];
		$r++;
	}

	return $data;
}

function categoricalProducts($category)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$sql = "SELECT * FROM products WHERE category='$category'";
	$query = mysqli_query($dbCon, $sql);
	$r = 0;

	while($row = mysqli_fetch_assoc($query)) 
	{
		$data[$r][0] = $row['name'];
		$data[$r][1] = $row['price'];
		$data[$r][2] = $row['image'];
		$data[$r][3] = $row['pID'];
		$data[$r][4] = $row['stock'];
		$r++;
	}

	return $data;
}

function updateProduct($data)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$pID = $data['pID'];
	// echo implode(" ",$data);
	$sql = "UPDATE products SET ";

	if($data['name'])
	{
		$name = $data['name'];
		$sql = $sql . "name = '$name',";

	} 

	if($data['price'])
	{
		$price = $data['price'];
		$sql = $sql . " price = '$price',";

	} 

	if($data['stock'])
	{
		$stock = $data['stock'];
		$sql = $sql . " stock = stock + '$stock',";

	} 

	if($data['description'])
	{
		$description= $data['description'];
		$sql = $sql . " description = '$description',";

	} 

	if($data['keywords'])
	{
		$keywords = $data['keywords'];
		$sql = $sql . " keywords = '$keywords',";

	} 

	if($data['category'] && $data['category']!="null")
	{
		$category = $data['category'];
		$sql = $sql . " category = '$category',";

	} 

	if($data['image'])
	{
		$image = addslashes($data['image']);
		$image = file_get_contents($image);	
		$image = base64_encode($image);
		$sql = $sql . " image = '$image',";

	} 

	$sql = rtrim($sql, ',');

	$sql = $sql . " WHERE pID='$pID'";

	// echo "\n" . $sql;

	$query = mysqli_query($dbCon, $sql);

	return $query;
}


function deleteProduct($pID)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$pID = cleanInput($dbCon, $pID);
	
	$sql = "DELETE FROM products WHERE pID='$pID'";
	$query = mysqli_query($dbCon, $sql);

	$sql = "DELETE FROM cart WHERE pID='$pID'";
	$query = mysqli_query($dbCon, $sql);

	return $query;
}


function searchedProducts($query)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$sql = "SELECT * FROM products WHERE keywords LIKE LOWER('%$query%')";
	$query = mysqli_query($dbCon, $sql);

	$r = 0;

	while($row = mysqli_fetch_assoc($query)) 
	{
		$data[$r][0] = $row['name'];
		$data[$r][1] = $row['price'];
		$data[$r][2] = $row['image'];
		$data[$r][3] = $row['pID'];
		$data[$r][4] = $row['stock'];
		$r++;
	}

	return $data;
}

function addToCart($pID, $uID)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");
	$pID = cleanInput($dbCon, $pID);

	$sql = "SELECT * FROM products WHERE pID='$pID'";
	$query = mysqli_query($dbCon, $sql);

	$_sql = "SELECT * FROM cart WHERE uID='$uID' AND pID='$pID'";
	$_query = mysqli_query($dbCon, $_sql);

	if(mysqli_num_rows($query) == 1 && mysqli_num_rows($_query) == 0)
	{
		
		$sql = "INSERT INTO cart (uID, pID, quantity) VALUES ('$uID', '$pID', '1')";
		$query = mysqli_query($dbCon, $sql);

		return "Successful";			
	}
	
	return null;
}


function getItemsAndPrice($uID)
{
	$items = 0;
	$price = 0;

	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$sql = "SELECT * FROM cart WHERE uID='$uID'";
	$query = mysqli_query($dbCon, $sql);

	while($row = mysqli_fetch_assoc($query)) 
	{
		$items++;
		$pID = $row['pID'];

		$_sql = "SELECT price FROM products WHERE pID='$pID'";
		$_query = mysqli_query($dbCon, $_sql);
		$_row = mysqli_fetch_row($_query);
		// echo $row['quantity'] . " " . $pID . " " . $_row[0] . "\n";
		$price = $price +  ($row['quantity'] * $_row[0]);
	}

	$data = array(            
            'items' => $items,
            'price' => $price            
        );

	return $data;

}

function cartItems($uID)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$sql = "SELECT * FROM cart WHERE uID='$uID'";
	$query = mysqli_query($dbCon, $sql);

	$r = 0;

	while($row = mysqli_fetch_assoc($query)) 
	{
		$pID = $row['pID'];

		$_sql = "SELECT name, price, image FROM products WHERE pID='$pID'";
		$_query = mysqli_query($dbCon, $_sql);
		$_row = mysqli_fetch_row($_query);
		// echo $row['quantity'] . " " . $pID . " " . $_row[0] . "\n";

		$data[$r][0] = $_row[0];
		$data[$r][1] = $row['quantity'];
		$data[$r][2] = $_row[1];
		$data[$r][3] = $pID;
		$data[$r][4] = $_row[2];

		$r++;
	}

	return $data;
}

function updateCart($uID, $update, $updatePID)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$cnt = count($update);
	$ret;

	for($i=0; $i<$cnt; $i++)
	{
		if($update[$i]>0)
		{
			$quantity = $update[$i];
			$quantity = cleanInput($dbCon, $quantity);
			$pID = $updatePID[$i];

			$sql = "SELECT quantity FROM cart WHERE uID='$uID' AND pID='$pID'";
			$query = mysqli_query($dbCon, $sql);
			$row = mysqli_fetch_row($query);
			$DBquantity = $row[0];

			// if($quantity < $DBquantity)
			// {
			// 	$sql = "UPDATE cart SET quantity = '$quantity' WHERE uID='$uID' AND pID='$pID'";
			// 	$query = mysqli_query($dbCon, $sql);
			// }

			// else if($quantity > $DBquantity)
			// {
				$sql = "SELECT stock FROM products WHERE pID='$pID'";
				$query = mysqli_query($dbCon, $sql);
				$row = mysqli_fetch_row($query);
				$availableStock = $row[0];
				// $stockNeeded = $quantity - $DBquantity;

				if($availableStock >= $quantity)
				{
					$sql = "UPDATE cart SET quantity = '$quantity' WHERE uID='$uID' AND pID='$pID'";
					$query = mysqli_query($dbCon, $sql);

					$ret[$i] = 0;
				}

				else 
				{
					if($availableStock == 0) $ret[$i] = -1;
					else $ret[$i] = $availableStock;
				}
			// }
			// else $ret[$i] = 0;
		}
		
		else $ret[$i] = 0;
	}

	return $ret;
}

// function updateCart($uID, $update, $updatePID)
// {
// 	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

// 	$cnt = count($update);

	
// 		if($update)
// 		{
// 			echo "GG";
// 			$quantity = $update;
// 			$quantity = cleanInput($dbCon, $quantity);
// 			$pID = $updatePID;

// 			$sql = "UPDATE cart SET quantity = '$quantity' WHERE uID='$uID' AND pID='$pID'";
// 			$query = mysqli_query($dbCon, $sql);
// 		}
	
// }

function removeFromCart($pID, $uID)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$pID = cleanInput($dbCon, $pID);
	
	$sql = "DELETE FROM cart WHERE uID='$uID' AND pID='$pID'";
	$query = mysqli_query($dbCon, $sql);


}

function productDetails($pID)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$sql = "SELECT name, price, category, description, image, stock FROM products WHERE pID='$pID'";
	$query = mysqli_query($dbCon, $sql);
	$row = mysqli_fetch_row($query);


	return $row;
}


function checkoutItems($data)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$check = true;

	$uID = $data[0];
	$phone = $data[1];
	$address = $data[2];
	$totalprice = 0;

	$sql = "SELECT pID, quantity FROM cart WHERE uID='$uID'";
	$query = mysqli_query($dbCon, $sql);

	$i = 0;
	while($row = mysqli_fetch_assoc($query)) 
	{
		$pID = $row['pID'];
		$quantity = $row['quantity'];

		$pIDarray[$i] = $pID;
		$quantityarray[$i] = $quantity;

		$_sql = "SELECT stock, price FROM products WHERE pID='$pID'";
		$_query = mysqli_query($dbCon, $_sql);
		$_row = mysqli_fetch_row($_query);
		$stock = $_row[0];


		$totalprice = $totalprice + ($quantity * $_row[1]);

		if($quantity > $stock)
		{
			$check = false;
			break;
		}

		$i++;
	}

	if($check)
	{
		$sql = "INSERT INTO orders (uID, address, phone, totalprice) VALUES ('$uID', '$address', '$phone', '$totalprice')";
		$query = mysqli_query($dbCon, $sql);

		$oID = mysqli_insert_id($dbCon);
		$cnt = $i;
		for($i=0; $i<$cnt; $i++)
		{
			$pID = $pIDarray[$i];
			$quantity = $quantityarray[$i];

			$sql = "INSERT INTO orderedItems (oID, pID, quantity) VALUES ('$oID', '$pID', '$quantity')";
			$query = mysqli_query($dbCon, $sql);

			$sql = "UPDATE products SET stock = stock - '$quantity', soldunits = soldunits + '$quantity' WHERE pID='$pID'";
			$query = mysqli_query($dbCon, $sql);

			$sql = "DELETE FROM cart WHERE uID='$uID'";
			$query = mysqli_query($dbCon, $sql);
		}

	}

	return $check;
}

?>