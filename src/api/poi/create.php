<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and required object files
include_once '../config/db.php';
include_once '../objects/poi.php';
include_once '../objects/category.php';

$db         = new db();
$conn       = $db->getConnection();

// initialize the poi object
$poi        = new Poi($conn);
$category   = new Category($conn);
$category->id = $_POST["category_id"];

// make sure necessary data is not empty
if(
    !empty($_POST["name"]) &&
    !empty($_POST["latitude"]) &&
    !empty($_POST["longitude"]) &&
    !empty($_POST["category_id"]) &&
    $category->exists()
){
    $poi->name      = $_POST["name"];
    $poi->latitude  = $_POST["latitude"];
    $poi->longitude = $_POST["longitude"];
    $poi->category  = $_POST["category_id"];
    $poi->created   = date('Y-m-d H:i:s');

    // create the poi
    if($poi->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Poi was created successfully."));
    } else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create poi."));
    }
}else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create poi. Data is incomplete."));
}