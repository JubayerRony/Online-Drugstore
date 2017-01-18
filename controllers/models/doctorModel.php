<?php 


include_once "myFunctions.php";

session_start();
session_regenerate_id();

function insertDoctor($data)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");


	$name = cleanInput($dbCon, $data['name']);
	$password = cleanInput($dbCon, $data['password']);
	$email = cleanInput($dbCon, $data['email']);
	$specialty = cleanInput($dbCon, $data['specialty']);

	$password = hashPassword($password);

	// echo "$name $username $password $email";

	$image = addslashes($data['image']);
	$image = file_get_contents($image);	
	$image = base64_encode($image);


	$sql = "INSERT INTO doctorInfo (name, email, password, specialty, image) 
							VALUES ('$name', '$email', '$password', '$specialty', '$image')";
	$query = mysqli_query($dbCon, $sql);
	return $query;
}

function authenticateDoctor($data)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");
	
	$email = cleanInput($dbCon, $data['email']);
	$password = cleanInput($dbCon, $data['password']);

	$sql = "SELECT email, password, dID, name FROM doctorInfo WHERE email='$email'";
	$query = mysqli_query($dbCon, $sql);

	if($query)
	{
		$row = mysqli_fetch_row($query);
		$DBemail = $row[0];
		$DBpassword = $row[1];

		if($email == $DBemail && password_verify($password, $DBpassword) == true)
		{
			$_SESSION['username'] = "sth";
			$_SESSION['name'] = $row[3];
			$_SESSION['isDoc'] = true;
			$_SESSION['isUser'] = false;			
			$_SESSION['isAdmin'] = false;
			$_SESSION['dID'] = $row[2];
			return "Success";
		}
		return null;
	}

	return null;

}

function doctorProfile($dID)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$sql = "SELECT * FROM doctorInfo WHERE dID='$dID'";
	$query = mysqli_query($dbCon, $sql);

	if($query)
	{
		$row = mysqli_fetch_row($query);

		$data = array(            
            'name' => $row[1],
            'email' => $row[2],
            'specialty' => $row[4],
            'image' => $row[5]        
        );

        return $data;
	}

	return null;
}

function schedule($dID)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$sql = "SELECT * FROM doctorSchedule WHERE dID='$dID' order by date asc, time asc";
	$query = mysqli_query($dbCon, $sql);
	$r = 0;

	while($row = mysqli_fetch_assoc($query)) 
	{
		$data[$r][0] = $row['date'];
		$data[$r][1] = $row['time'];
		$data[$r][2] = $row['maxapp'];
		$data[$r][3] = $row['apptaken'];
		$data[$r][4] = $row['sID'];
		$r++;
	}

	return $data;
}


function addSchedule($data)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$dID = cleanInput($dbCon, $data['dID']);
	$time = cleanInput($dbCon, $data['time']);
    $date = cleanInput($dbCon, $data['date']);
	$maxapp = cleanInput($dbCon, $data['maxapp']);   
	$apptaken = 0;

	if($maxapp > 0)
	{

		$time = $time .":00";
	    $date = substr($date, 6, 4) . substr($date, 2, 4) . substr($date, 0, 2);

		$sql = "INSERT INTO doctorSchedule (dID, date, time, maxapp, apptaken) 
								VALUES ('$dID', '$date', '$time', '$maxapp', '$apptaken')";

		$query = mysqli_query($dbCon, $sql);

		return $query;
	}

	return null;
}


function deleteSchedule($sID, $dID)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$sql = "SELECT date, time, dID FROM doctorSchedule WHERE sID='$sID' AND dID='$dID'";
    $query = mysqli_query($dbCon, $sql);
    if(mysqli_num_rows($query))
    {
	    $row = mysqli_fetch_row($query);


	    $date = $row[0];
	    $time = $row[1];

	    $sql = "SELECT name FROM doctorInfo WHERE dID='$dID'";
	    $query = mysqli_query($dbCon, $sql);
	    $row = mysqli_fetch_row($query);
	    $doctorName = $row[0];

		$sql = "SELECT * FROM appointments WHERE sID='$sID'";
	    $query = mysqli_query($dbCon, $sql);
	    while($row = mysqli_fetch_assoc($query))
	    {
	    	$uID = $row['uID'];

	    	$_sql = "SELECT email FROM userInfo WHERE uID='$uID'";
	    	$_query = mysqli_query($dbCon, $_sql);
	    	$_row = mysqli_fetch_row($_query);

	    	$userEmail = $_row[0];

	    	sendMail($userEmail, "Doctor Schedule Cancelled",
			"Your appointment with $doctorName on $date at $time has been cancelled by the doctor.",
			"From: webmaster@example.com" . "\r\n" .
			"CC: somebodyelse@example.com");
	    }

		$sql = "DELETE FROM doctorSchedule WHERE sID='$sID'";
		$query = mysqli_query($dbCon, $sql);
	}
}
	


function allDoctors()
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$sql =  "SELECT * FROM doctorInfo order by name asc";
	$query = mysqli_query($dbCon, $sql);
	$r = 0;

	while($row = mysqli_fetch_assoc($query)) 
	{
		$data[$r][0] = $row['dID'];
		$data[$r][1] = $row['name'];
		$data[$r][2] = $row['email'];
		$data[$r][3] = $row['specialty'];
		$data[$r][4] = $row['image'];
		$r++;
	}

	return $data;
}

function recoverDocPassword($email)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$email = cleanInput($dbCon, $email);

	$sql =  "SELECT email FROM doctorInfo WHERE email='$email'";
	$query = mysqli_query($dbCon, $sql);
	if(mysqli_num_rows($query))
	{
		$recoverytoken = generateRandomString();
		$sql =  "UPDATE doctorInfo SET recoverytoken = '$recoverytoken' WHERE email='$email'";
		$query = mysqli_query($dbCon, $sql);

		sendMail($email, "Password Recovery",
		"Your password recovery request has been acknowledged.\n\nYour Recovery Token is $recoverytoken\n\n
		Go To http://localhost/doctor/recoverPassword.php and use your recovery token to setup a new password.",
		"From: webmaster@example.com" . "\r\n" .
		"CC: somebodyelse@example.com");

		return "Success";
	}

	return null;
}

function changeDocPassword($data)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$email = $data['email'];
	$password = $data['password'];
	$recoverytoken = $data['recoverytoken'];

	$email = cleanInput($dbCon, $email);
	$password = cleanInput($dbCon, $password);
	$recoverytoken = cleanInput($dbCon, $recoverytoken);

	$sql =  "SELECT email, recoverytoken FROM doctorInfo WHERE email='$email'";
	$query = mysqli_query($dbCon, $sql);

	if(mysqli_num_rows($query))
	{
		$row = mysqli_fetch_row($query);

		if($row[1] == $recoverytoken)
		{
			$password = hashPassword($password);
			$sql =  "UPDATE doctorInfo SET password='$password', recoverytoken = NULL WHERE recoverytoken='$recoverytoken'";
			$query = mysqli_query($dbCon, $sql);

			return "Success";
		}		

		return "token";
	}

	return "email";

}

?>