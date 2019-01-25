<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and required object files
include_once '../config/db.php';
include_once '../objects/user.php';

$db = new db();
$conn = $db->getConnection();

// initialize the poi object
$user = new User($conn);

if(!empty($_POST["id"])){
    $user->id = $_POST["id"];

    /**
     * ONLY CERTAIN USERS (ADMINS) SHOULD BE ABLE TO CREATE USERS
     */
    if ($user->delete()) {
        http_response_code(200);
        echo json_encode(array("message" => "User was deleted."));
    }else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete user."));
    }
}else{
    http_response_code(503);
    echo json_encode(array("message" => "You need to specify a user id."));
}