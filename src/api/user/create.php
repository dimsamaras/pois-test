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

// make sure necessary data is not empty
if( !empty($_POST["firstname"]) &&
        !empty($_POST["lastname"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["password"]) &&
        !$user->exists($_POST["email"])
    ){

    $user->firstname    = $_POST["firstname"];
    $user->lastname     = $_POST["lastname"];
    $user->email        = $_POST["email"];
    $user->password     = $_POST["password"];
    $user->created      = date('Y-m-d H:i:s');

    /**
     * ONLY CERTAIN USERS (ADMINS) SHOULD BE ABLE TO CREATE USERS
     */
    // create the user
    if($user->create()){
        http_response_code(201);
        echo json_encode(array("message" => "User was created successfully."));
    } else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create user."));
    }
}else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create user. Data is incomplete or user email is in use."));
}