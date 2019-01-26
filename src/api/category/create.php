<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and required object files
include_once '../config/db.php';
include_once '../validate_token.php';
include_once '../objects/category.php';

validate($_SERVER['HTTP_AUTHORIZATION']);

$db = new db();
$conn = $db->getConnection();

// initialize the poi object
$category = new Category($conn);
// make sure necessary data is not empty
if( !empty($_POST["name"])){
    $category->name = $_POST["name"];
    $category->created = date('Y-m-d H:i:s');

    // create the category
    if($category->create()){
        http_response_code(201);
        echo json_encode(array("id"=>$category->id,"message" => "Category was created successfully."));
    } else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create category."));
    }
}else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create Category. Data is incomplete."));
}