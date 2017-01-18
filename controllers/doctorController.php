<?php 

include_once "models/doctorModel.php";

class doctorController
{

	public function __construct() { 
		
	}

	

	public function registerDoctor($data)
	{		
		if(insertDoctor($data)) return "Successful";
		return "Error";
	}



	public function loginDoctor($data)
	{
		if(authenticateDoctor($data)) return "Successful";
		return "Error";
	}

	public function  getDoctorProfile($dID)
	{
		return doctorProfile($dID);
	}

	public function getDoctorSchedule($dID)
	{
		return schedule($dID);
	}

	public function addDoctorSchedule($data)
	{
		if(addSchedule($data)) return "Successful";
		return "Error";
	}

	public function cancelDoctorSchedule($sID, $dID)
	{
		deleteSchedule($sID, $dID);
	}

	public function getAllDoctors()
	{
		return allDoctors();
	}

	public function recoverDoctorPassword($email)
	{
		return recoverDocPassword($email);
	}

	public function changeDoctorPassword($data)
	{
		return changeDocPassword($data);
	}


}



$doctorController = new doctorController();

?>