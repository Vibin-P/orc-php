<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'db_connection.php';

// POST DATA
$data = json_decode(file_get_contents("php://input"));
if(isset($data->user_name) 
    && isset($data->password) 
	&& isset($data->user_email) 
	&& !empty(trim($data->user_name)) 
	&& !empty(trim($data->password))
	){
    $user_name = mysqli_real_escape_string($db_conn, trim($data->user_name));
    $password = mysqli_real_escape_string($db_conn, trim($data->password));
    $user_email = mysqli_real_escape_string($db_conn, trim($data->user_email));
    $en_password = md5($password);
    $sql_u = "SELECT * FROM users WHERE username='$user_name'";
    $res_u = mysqli_query($db_conn, $sql_u);
    if (mysqli_num_rows($res_u) > 0) {
        
         echo json_encode(["Sorry... username already taken"]);
      
    }
    else{
        $insertUser = mysqli_query($db_conn,"INSERT INTO `users`(`username`,`email`,`pwd`) VALUES('$user_name','$user_email','$en_password')");

        if($insertUser){
            
            echo json_encode(["success"]);
        }
        else{
            echo json_encode(["failed"]);
        }
    
   }
    
}
else{
    echo json_encode(["success"=>0,"msg"=>"Please fill all the required fields!"]);
}