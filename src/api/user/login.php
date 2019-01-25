<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and required object files
include_once '../config/db.php';
include_once '../config/settings.php';
include_once '../objects/user.php';
include_once '../libs/php-jwt/src/BeforeValidException.php';
include_once '../libs/php-jwt/src/ExpiredException.php';
include_once '../libs/php-jwt/src/SignatureInvalidException.php';
include_once '../libs/php-jwt/src/JWT.php';
use \Firebase\JWT\JWT;

$db = new db();
$conn = $db->getConnection();

// initialize the poi object
$user = new User($conn);
$userExists = $user->exists($_POST["email"]);

if( !empty($_POST["email"]) &&
    !empty($_POST["password"]) &&
    $userExists &&
    password_verify($_POST["password"], $user->password)){

    $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => $iat,
        "nbf" => $nbf,
        "data" => array(
            "id" => $user->id,
            "email"=>$user->email
        )
    );

    http_response_code(200);
    $jwt = JWT::encode($token, $key);
    echo json_encode(
        array(
            "message" => "Successful login.",
            "jwt" => $jwt
        )
    );

}else{
    http_response_code(401);
    echo json_encode(array("message" => "Login failed."));
}
