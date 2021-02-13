<?php
$url = "localhost";
	$database = "orc_db" ;
	$username ="root";
	$password  = "";

	$conn  = mysqli_connect($url,$username, $password, $database);
$body = file_get_contents('php://input');

$tv = json_decode($body);
/*echo '<pre>';
print_r($object);
echo '</pre>';*/
print_r($tv);

	$name =  $tv['name'];
	$age =  $tv['age'];
	echo $age;
	$sql = "INSERT INTO `data`( `T9`, `SV1`, `SV2`, `SV3`, `SV4`, `T1`, `T2`) VALUES ($name,$age,1,1,1,1,1);";
	$qur = mysqli_query($conn,$sql);
	if($qur){
		$json = array("status" => 1, "msg" => "Done User added!");
	}else{
		$json = array("status" => 0, "msg" => "Error adding user!");
	}

?>