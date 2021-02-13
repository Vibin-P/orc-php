<?php

	$url = "localhost";
	$database = "orc_db" ;
	$username ="root";
	$password  = "";

	$conn  = mysqli_connect($url,$username, $password, $database);

	if(!$conn)
	{
		die("connection faild:" .$conn-> connect_error);

	}

	$sql  = "select * from data";
	$result  = mysqli_query($conn,$sql);

	$rows  = array();

	if(mysqli_num_rows($result) > 0){
		while ($r  = mysqli_fetch_assoc($result)) {
			array_push($rows, $r);
			# code...
		}
		http_response_code(200);
		print json_encode($rows);

	}

	else
	{
		http_response_code(200);
		echo "no data";

	}

	mysqli_close($conn);


?>