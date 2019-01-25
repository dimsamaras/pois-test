<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and required object files
include_once '../config/db.php';
include_once '../objects/category.php';

$db = new db();
$conn = $db->getConnection();

// initialize the poi object
$category = new Category($conn);

if(!empty($_POST["id"])){
    $category->id = $_POST["id"];

    if ($category->delete()) {
        http_response_code(200);
        echo json_encode(array("message" => "Category was deleted."));
    }else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete category."));
    }
}else{
    http_response_code(503);
    echo json_encode(array("message" => "You need to specify a category id."));
}