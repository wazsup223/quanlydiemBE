<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/subjects.php';

$database = new Database();
$db = $database->getConnection();

$subject = new Subject($db);

$data = json_decode(file_get_contents("php://input"));

$response = array(
    "status" => "success",
    "message" => "",
    "data" => null
);

if(!empty($data->id) && !empty($data->name) && !empty($data->credits) && 
   !empty($data->process_percentage) && !empty($data->midterm_percentage) && !empty($data->final_percentage) && 
   !empty($data->created_at) && isset($data->updated_at) && !empty($data->department_id)
   ) {
    
    $subject->id = $data->id;
    $subject->name = $data->name;
    $subject->credits = $data->credits;
    $subject->process_percentage = $data->process_percentage;
    $subject->midterm_percentage = $data->midterm_percentage;
    $subject->final_percentage = $data->final_percentage;
    $subject->created_at = $data->created_at;
    $subject->updated_at = $data->updated_at;
    $subject->department_id = $data->department_id;

    if($subject->create()) {
        $response["message"] = "Thêm môn học thành công";
        $response["data"] = array(
            "id" => $subject->id,
            "name" => $subject->name,
            "credits" => $subject->credits,
            "process_percentage" => $subject->process_percentage,
            "midterm_percentage" => $subject->midterm_percentage,
            "final_percentage" => $subject->final_percentage,
            "created_at" => $subject->created_at,
            "updated_at" => $subject->updated_at,
            "department_id" => $subject ->department_id
        );
        http_response_code(201);
    } else {
        $response["status"] = "error";
        $response["message"] = "Không thể thêm môn học";
        http_response_code(503);
    }
} else {
    $response["status"] = "error";
    $response["message"] = "Dữ liệu không đầy đủ";
    http_response_code(400);
}

echo json_encode($response);
?> 