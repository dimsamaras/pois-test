<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// include database and required object files
include_once '../config/db.php';
include_once '../objects/poi.php';

$db = new db();
$conn = $db->getConnection();

// initialize the poi object
$poi = new Poi($conn);

$poi->id = isset($_GET['id']) ? $_GET['id'] : die('You need to specify a POI id for this call');

// retrieve single poi
$onePoi = $poi->retrieve_one();

if($poi->id > 0 && $poi->name != null){ //
    http_response_code(200);
    echo json_encode($onePoi);
}else{
    http_response_code(404);
    echo json_encode(
        array("pois"=>false, "message" => "No pois registered with this id.")
    );
}
