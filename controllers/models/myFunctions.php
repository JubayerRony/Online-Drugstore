<?php



function cleanInput($dbCon, $value)
{
	$value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	$value =  mysqli_real_escape_string($dbCon, $value);
	return $value;
}

function hashPassword($value)
{
	return password_hash($value, PASSWORD_DEFAULT, ['cost' => 12]);
}

function sendMail($to, $subject, $txt, $headers)
{
	mail($to,$subject,$txt,$headers);
}

function generateRandomString($length = 10) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

?>