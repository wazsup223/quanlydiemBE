<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/Instructor.php';

$database = new Database();
$db = $database->getConnection();

$instructor = new Instructor($db);

$data = json_decode(file_get_contents("php://input"));

$response = array(
    "status" => "success",
    "message" => "",
    "data" => null
);

if(!empty($data->id) && !empty($data->first_name) && !empty($data->last_name) && 
   !empty($data->email) && !empty($data->address) && !empty($data->birth_day) && 
   !empty($data->phone) && isset($data->gender) && !empty($data->degree) && 
   !empty($data->department_id)) {
    
    $instructor->id = $data->id;
    $instructor->first_name = $data->first_name;
    $instructor->last_name = $data->last_name;
    $instructor->email = $data->email;
    $instructor->address = $data->address;
    $instructor->birth_day = $data->birth_day;
    $instructor->phone = $data->phone;
    $instructor->gender = $data->gender;
    $instructor->degree = $data->degree;
    $instructor->department_id = $data->department_id;

    if($instructor->update()) {
        $response["message"] = "Cập nhật thông tin giảng viên thành công";
        $response["data"] = array(
            "id" => $instructor->id,
            "first_name" => $instructor->first_name,
            "last_name" => $instructor->last_name,
            "email" => $instructor->email,
            "address" => $instructor->address,
            "birth_day" => $instructor->birth_day,
            "phone" => $instructor->phone,
            "gender" => $instructor->gender,
            "degree" => $instructor->degree,
            "department_id" => $instructor->department_id
        );
        http_response_code(200);
    } else {
        $response["status"] = "error";
        $response["message"] = "Không thể cập nhật thông tin giảng viên";
        http_response_code(503);
    }
} else {
    $response["status"] = "error";
    $response["message"] = "Dữ liệu không đầy đủ";
    http_response_code(400);
}

echo json_encode($response);
?> 