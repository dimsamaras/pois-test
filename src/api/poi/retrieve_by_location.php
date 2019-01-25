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

$poi->latitude   = isset($_GET['lat']) ? $_GET['lat'] : die('You need to specify a latitude for this call');
$poi->longitude  = isset($_GET['lng']) ? $_GET['lng'] : die('You need to specify a longitude id for this call');
$closeDistance   = isset($_GET['distance']) ? $_GET['distance'] : 100;

// retrieve single poi
$pois = $poi->retrieve_by_location($closeDistance);

if(!empty($pois)){
    http_response_code(200);
    echo json_encode($pois);
}else{
    http_response_code(404);
    echo json_encode(
        array("pois"=>false, "message" => "No pois registered close to these coordinates.")
    );
}
