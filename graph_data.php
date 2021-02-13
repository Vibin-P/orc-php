<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'db_connection.php';

// POST DATA
$data = json_decode(file_get_contents("php://input"));
if(isset($data->start_date) 
    && isset($data->end_date) 
    && !empty(trim($data->start_date)) 
    && !empty(trim($data->end_date))
    ){
    $start_date = mysqli_real_escape_string($db_conn, trim($data->start_date));
    $end_date = mysqli_real_escape_string($db_conn, trim($data->end_date));
        $insertUser = mysqli_query($db_conn,"SELECT * FROM data WHERE date_Time BETWEEN '" . $start_date . "' AND  '" . $end_date . "' ORDER by data_id DESC");

        $count = mysqli_num_rows($insertUser);  
        $rows  = array();
        if($count > 0){  
            while ($r  = mysqli_fetch_assoc($insertUser)) {
            array_push($rows, $r);
            # code...
            }  
            //$last_id = mysqli_insert_id($db_conn);
            print json_encode($rows);

            //echo json_encode(["Login success"]);
        }
        else{
            echo json_encode(["login failed"]);
        }  
   
    
}
else{
    echo json_encode(["success"=>0,"msg"=>"Please fill all the required fields!"]);
}