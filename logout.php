<html>
<head> 
	<title>Logout</title>	
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<form method="" action="index.php">
		<button type="submit" value="Submit" name="submit">Home</button> 
</form> 

<body>


<?php 

session_start();
session_regenerate_id(true);

// Unsetting all of the session variables.
$_SESSION = array();

// Deleting Session Cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroying the session.
session_destroy();
header("Location: index.php");
?>



</body>
</html>