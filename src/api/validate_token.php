<?php

include_once 'config/settings.php';
include_once 'libs/php-jwt/src/BeforeValidException.php';
include_once 'libs/php-jwt/src/ExpiredException.php';
include_once 'libs/php-jwt/src/SignatureInvalidException.php';
include_once 'libs/php-jwt/src/JWT.php';
use \Firebase\JWT\JWT;

function validate($jwt)
{
    global $key;

    if ($jwt) {
        try {
            JWT::decode($jwt, $key, array('HS256'));
            return true;
        } catch (Exception $e) {

            http_response_code(401);

            echo json_encode(array(
                "message" => "Access denied.",
                "error"   => $e->getMessage()
            ));

            die();
        }
    }else{
        http_response_code(401);

        echo json_encode(array(
            "message" => "Access denied.",
            "error"   => "No authorization token provided"
        ));

        die();
    }
}