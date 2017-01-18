<?php 

include_once "models/userModel.php";

class userController
{

	public function __construct() { 
		
	}

	

	public function registerUser($data)
	{		
		if(insertUser($data)) return "Successful";
		return "Error";
	}



	public function loginUser($data)
	{
		if(authenticateUser($data)) return "Successful";
		return "Error";
	}

	public function takeAppointment($sID, $uID)
	{
		if(makeAppointment($sID, $uID)) return "Successful";
		return "Error";
	}

	public function appointmentTaken($sID, $uID)
	{
		return checkAppointment($sID, $uID);
	}

	public function loginAdmin($data)
	{
		if(authenticateAdmin($data)) return "Successful";
		return "Error";
	}

	public function  getUserProfile($uID)
	{
		return userProfile($uID);
	}

	public function getUserAppointments($uID)
	{
		return userAppointments($uID);
	}

	public function cancelUserAppointment($uID, $aID, $sID)
	{
		deleteAppointment($uID, $aID, $sID);
	}

	public function recoverUserPassword($email)
	{
		return recoverUsrPassword($email);
	}

	public function changeUserPassword($data)
	{
		return changeUsrPassword($data);
	}
}



$userController = new userController();

?>