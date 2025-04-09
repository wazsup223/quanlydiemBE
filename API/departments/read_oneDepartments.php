<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/departments.php';

$database = new Database();
$db = $database->getConnection();

$departments = new Departments($db);

$data = json_decode(file_get_contents("php://input"));

$response = array(
    "status" => "success",
    "message" => "",
    "data" => null
);

if (!empty($data->id)) {
    $departments->id = $data->id;
    $departments->readOne();

    if ($departments->name != null) {
        $response["data"] = array(
            "id" => $departments->id,
            "symbol" => $departments->symbol,
            "name" => $departments->name
        );
        $response["message"] = "Lấy thông tin khoa thành công";
        http_response_code(200);
    } else {
        $response["status"] = "error";
        $response["message"] = "Không tìm thấy khoa";
        http_response_code(404);
    }
} else {
    $response["status"] = "error";
    $response["message"] = "Dữ liệu không đầy đủ";
    http_response_code(400);
}

echo json_encode($response);

?>