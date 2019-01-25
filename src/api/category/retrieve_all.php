<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and required object files
include_once '../config/db.php';
include_once '../validate_token.php';
include_once '../objects/category.php';

validate($_SERVER['HTTP_AUTHORIZATION']);

$db      = new db();
$conn    = $db->getConnection();

// initialize the category object
$category = new Category($conn);

// retrieve categories
$categories = $category->retrieve();

if (!empty($categories)) {
    http_response_code(200);
    echo json_encode($categories);
} else {
    // no categories exist in the db.
    http_response_code(404);
    echo json_encode(
        array("categories" => false, "message" => "No categories registered.")
    );
}