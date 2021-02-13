<?php

require_once dirname(__FILE__) . '/ModbusMasterTCP.php';

// Create Modbus object
$modbus = new ModbusMaster("192.168.1.120", "TCP");
 while(1) {
try {
    // FC 3
    $T1 = $modbus->readMultipleRegisters(1, 15, 1);
    $T2 = $modbus->readMultipleRegisters(1, 16, 1);
    $T9 = $modbus->readMultipleRegisters(1, 17, 1);
    $RPM = $modbus->readMultipleRegisters(1, 18, 1);
    $Dummy = $modbus->readMultipleRegisters(1, 19, 1);
    $P2 = $modbus->readMultipleRegisters(1, 20, 1);
}
catch (Exception $e) {
    // Print error information if any
    echo $modbus;
    echo $e;
    exit;
}

// Print status information


// Print read data
echo "</br>Data:</br>";
//print_r($recData); 
echo $T1[1];echo "</br>";
echo $T2[1];echo "</br>";
echo $T9[1];echo "</br>";
echo $RPM[1];echo "</br>";
echo $Dummy[1];echo "</br>";
echo $P2[1];echo "</br>";
echo "</br>";
$url = "localhost";
	$database = "orc_db" ;
	$username ="root";
	$password  = "";
	$conn  = mysqli_connect($url,$username, $password, $database);

	if(!$conn)
	{
		die("connection faild:" .$conn-> connect_error);

	}

	$sql  = "INSERT INTO `data`(`T1`, `T2`, `T9`, `RPM`, `P2`, `date_Time`) VALUES ('$T1[1]','$T2[1]','$T9[1]','$RPM[1]','$P2[1]', now())";
	$result  = mysqli_query($conn,$sql);
	sleep(3);
	
}
mysqli_close($conn);
?>