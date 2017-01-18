<?php


class dbhelper{
	 //$conn = "";
	 //$servername = "localhost";
	 //$username = "ab1d";
	 //$password = "123456";
	 //$dbname = "logininfo";
	public function __construct() { 
		

		}
	
	// public function insert($tablename, $columns, $values, $types){
	// 	$conn = "";
	//  $servername = "localhost";
	//  $username = "root";
	//  $password = "root";
	//  $dbname = "doctor";
	// 	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// 	//$sql = "INSERT INTO members (username, password, email) VALUES ('$username', '$password', '$email')";
	// 	$sql = "INSERT INTO $tablename (";
	// 	$len = count($columns);
	// 	$sql = $sql . $columns[0];

	// 	for($i=1; $i<$len; $i++)
	// 	{
	// 		$sql = $sql . ", " . $columns[$i];
	// 	}
	// 	$sql = $sql . ") VALUES (";

	// 	$len = count($values);
	// 	$sql = $sql . "'$values[0]'";
	// 	for($i=1; $i<$len; $i++)
	// 	{
	// 		$sql = $sql . ", '$values[$i]'";
	// 	}
	// 	$sql = $sql . ")";
	// 	$query = mysqli_query($conn, $sql);

	// 	return $query;
		
	// }
}



$db = new dbhelper();




?>