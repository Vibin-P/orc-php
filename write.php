<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
$url = "localhost";
	$database = "orc_db" ;
	$username ="root";
	$password  = "";

	$conn  = mysqli_connect($url,$username, $password, $database);


//if($_SERVER['REQUEST_METHOD'] == "POST"){
	// Get data
	/*$testingdetails = $_POST['testingdetails'];
	$S1 = $_POST['S1'];
	$S2 = $_POST['S2'];
	$S3 =$_POST['S3'];
	$S4 = $_POST['S4'];
	$date = $_POST['date'];
	$time = $_POST['time'];*/

	// Insert data into data base
	//$sql = "INSERT INTO `orcdata`.`users` (`ID`, `name`, `email`, `password`, `status`) VALUES (NULL, '$name', '$email', '$password', '$status');";
	//$sql = "INSERT INTO `data`( `testingdetails`, `S1`, `S2`, `S3`, `S4`, `date`, `time`) VALUES ($testingdetails,$S1,$S2,$S3,$S4,'$date','	$time');";
	//$sql = "INSERT INTO `data`( `T9`, `SV1`, `SV2`, `SV3`, `SV4`, `T1`, `T2`) VALUES (1,1,1,1,1,1,1);";
	$data = json_decode(file_get_contents("php://input"));
printr $data;
	$sql = "INSERT INTO `data`( `T9`, `SV1`, `SV2`, `SV3`, `SV4`, `T1`, `T2`) VALUES (1,1,1,1,1,1,1);";
	$qur = mysqli_query($conn,$sql);
	if($qur){
		$json = array("status" => 1, "msg" => "Done User added!");
	}else{
		$json = array("status" => 0, "msg" => "Error adding user!");
	}
//}
/*else{
	$json = array("status" => 0, "msg" => "Request method not accepted");
}*/

@mysqli_close($conn);

/* Output header */
	header('Content-type: application/json');
	echo json_encode($json);
	?>