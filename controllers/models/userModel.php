<?php 


include_once "myFunctions.php";

session_start();
session_regenerate_id();

function insertUser($data)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");


	$name = cleanInput($dbCon, $data['name']);
	$password = cleanInput($dbCon, $data['password']);
	$email = cleanInput($dbCon, $data['email']);

	$password = hashPassword($password);

	// echo "$name $username $password $email";

	$image = addslashes($data['image']);
	$image = file_get_contents($image);	
	$image = base64_encode($image);


	$sql = "INSERT INTO userInfo (name, email, password,  image) VALUES ('$name', '$email', '$password', '$image')";
	$query = mysqli_query($dbCon, $sql);
	return $query;
}

function authenticateUser($data)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");
	
	$email = cleanInput($dbCon, $data['email']);
	$password = cleanInput($dbCon, $data['password']);

	$sql = "SELECT email, password, uID, name FROM userInfo WHERE email='$email'";
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
			$_SESSION['isDoc'] = false;
			$_SESSION['isUser'] = true;			
			$_SESSION['isAdmin'] = false;
			$_SESSION['uID'] = $row[2];
			return "Success";
		}
		return null;
	}

	return null;

}

function userProfile($uID)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$sql = "SELECT * FROM userInfo WHERE uID='$uID'";
	$query = mysqli_query($dbCon, $sql);

	if($query)
	{
		$row = mysqli_fetch_row($query);

		$data = array(            
            'name' => $row[1],
            'email' => $row[2],
            'image' => $row[4]        
        );

        return $data;
	}

	return null;
}

function userAppointments($uID)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$sql = "SELECT * FROM appointments WHERE uID='$uID'";
    $query = mysqli_query($dbCon, $sql);
    if($query)
    {
    	$r = 0;
    	while($row = mysqli_fetch_assoc($query))
        {
        	$aID = $row["aID"];
            $sID = $row["sID"];
            $sql = "SELECT * FROM doctorSchedule WHERE sID='$sID'";
            $_query = mysqli_query($dbCon, $sql);
            $_row = mysqli_fetch_assoc($_query);
            $appDate = $_row["date"];
            $appTime = $_row["time"];
            $dID = $_row["dID"];

            $sql = "SELECT * FROM doctorInfo WHERE dID='$dID'";
            $_query = mysqli_query($dbCon, $sql);
            $_row = mysqli_fetch_assoc($_query);
            $doctorName = $_row["name"];

            $appDate = substr($appDate, 8, 2) . substr($appDate, 4, 4) . substr($appDate, 0, 4); 

            $data[$r][0] = $appDate;
			$data[$r][1] = $appTime;
			$data[$r][2] = $doctorName;
			$data[$r][3] = $aID; 
			$data[$r][4] = $sID;

			$r++;              
       
        }

        return $data;
    }

    return $data;
}

function deleteAppointment($uID, $aID, $sID)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$aID = cleanInput($dbCon, $aID);
	$sID = cleanInput($dbCon, $sID);

	$sql = "DELETE FROM appointments WHERE aID='$aID' AND uID='$uID'";
    $query = mysqli_query($dbCon, $sql);
    if(mysqli_affected_rows($dbCon))
    {
    	$sql = "UPDATE doctorSchedule SET apptaken = apptaken - 1 WHERE sID='$sID'";
        $query = mysqli_query($dbCon, $sql);
    }
}


function authenticateAdmin($data)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");
	
	$username = cleanInput($dbCon, $data['username']);
	$password = cleanInput($dbCon, $data['password']);

	$sql = "SELECT username, password, adID FROM adminInfo WHERE username='$username'";
	$query = mysqli_query($dbCon, $sql);

	if($query)
	{
		$row = mysqli_fetch_row($query);
		$DBusenrame = $row[0];
		$DBpassword = $row[1];

		if($username == $DBusenrame && $password == $DBpassword)
		{
			$_SESSION['username'] = $username;	
			$_SESSION['name'] = "Admin";		
			$_SESSION['isDoc'] = false;
			$_SESSION['isUser'] = false;
			$_SESSION['isAdmin'] = true;
			$_SESSION['adID'] = $row[2];
			return "Success";
		}
		return null;
	}

	return null;


}
	

function checkAppointment($sID, $uID)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");
	$sql = "SELECT * FROM appointments WHERE sID='$sID' AND uID='$uID'";
	$query = mysqli_query($dbCon, $sql);
	return mysqli_num_rows($query);
}

function makeAppointment($sID, $uID)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$sql = "SELECT * FROM doctorSchedule WHERE sID='$sID'";
	$query = mysqli_query($dbCon, $sql);

	$_sql = "SELECT * FROM appointments WHERE sID='$sID' AND uID='$uID'";
	$_query = mysqli_query($dbCon, $_sql);

	if(mysqli_num_rows($query) == 1 && mysqli_num_rows($_query) == 0)
	{
		$sql = "INSERT INTO appointments (sID, uID) VALUES ('$sID', '$uID')";
		$query = mysqli_query($dbCon, $sql);		

		$sql = "UPDATE doctorSchedule SET apptaken = apptaken + 1 WHERE sID='$sID'";
		$query = mysqli_query($dbCon, $sql);

		return $query;
	}

	return null;
}


function recoverUsrPassword($email)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$email = cleanInput($dbCon, $email);

	$sql =  "SELECT email FROM userInfo WHERE email='$email'";
	$query = mysqli_query($dbCon, $sql);
	if(mysqli_num_rows($query))
	{
		$recoverytoken = generateRandomString();
		$sql =  "UPDATE userInfo SET recoverytoken = '$recoverytoken' WHERE email='$email'";
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

function changeUsrPassword($data)
{
	$dbCon = mysqli_connect("localhost", "root", "root", "doctor");

	$email = $data['email'];
	$password = $data['password'];
	$recoverytoken = $data['recoverytoken'];

	$email = cleanInput($dbCon, $email);
	$password = cleanInput($dbCon, $password);
	$recoverytoken = cleanInput($dbCon, $recoverytoken);

	$sql =  "SELECT email, recoverytoken FROM userInfo WHERE email='$email'";
	$query = mysqli_query($dbCon, $sql);

	if(mysqli_num_rows($query))
	{
		$row = mysqli_fetch_row($query);

		if($row[1] == $recoverytoken)
		{
			$password = hashPassword($password);
			$sql =  "UPDATE userInfo SET password='$password', recoverytoken = NULL WHERE recoverytoken='$recoverytoken'";
			$query = mysqli_query($dbCon, $sql);

			return "Success";
		}		

		return "token";
	}

	return "email";

}

?>