<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and required object files
include_once '../config/db.php';
include_once '../objects/poi.php';

$db = new db();
$conn = $db->getConnection();

// initialize the poi object
$poi = new Poi($conn);

// retrieve pois
$pois = $poi->retrieve();

if(!empty($pois)){
    http_response_code(200);
    echo json_encode($pois);
}else{
    // no pois exist in the db.
    http_response_code(404);
    echo json_encode(
        array("pois"=>false, "message" => "No pois registered.")
    );
}
