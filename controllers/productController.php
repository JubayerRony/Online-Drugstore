<?php 

include_once "models/productModel.php";

class productController
{

	public function __construct() { 
		
	}

	public function getAllProducts()
	{
		return allProducts();
	}

	public function getSearchedProducts($query)
	{
		return searchedProducts($query);
	}
	
	public function addProductToCart($pID, $uID)
	{
		if(addToCart($pID, $uID)) return "Successful";
		return "Error";
	}
	
	public function getTotalItemsAndPrice($uID)
	{
		return getItemsAndPrice($uID);
	}

	public function getCartItems($uID)
	{
		return cartItems($uID);
	}

	public function updateTheCart($uID, $update, $updatePID)
	{
		return updateCart($uID, $update, $updatePID);
	}

	public function removeProductFromCart($pID, $uID)
	{
		removeFromCart($pID, $uID);
	}

	public function addProduct($data)
	{
		if(insertProduct($data)) return "Successful";
		return "Error";
	}

	public function editProduct($data)
	{
		if(updateProduct($data)) return "Successful";
		return "Error";
	}

	public function removeProduct($data)
	{
		if(deleteProduct($data)) return "Successful";
		return "Error";
	}

	public function getProductByCategory($category)
	{
		return categoricalProducts($category);
	}

	public function getLatesProducts()
	{
		return latestProducts();
	}

	public function getProductDetails($pID)
	{
		return productDetails($pID);

	}

	public function checkoutCartItems($data)
	{
		return checkoutItems($data);
	}

	public function getMostPopularProducts()
	{
		return mostPopularProducts();
	}
}



$productController = new productController();

?>