<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and required object files
include_once '../config/db.php';
include_once '../config/settings.php';
include_once '../objects/poi.php';

$db = new db();
$conn = $db->getConnection();

// initialize the poi object
$poi = new Poi($conn);

//// retrieve pois
//// make getPaging public to test and uncomment
// $paging = $poi->getPaging(8, 40, 5, "localhost:81/poi/retrieve_all?");

if(!empty($paging)){
    http_response_code(200);
    echo json_encode($paging);
}else{
    // no pois exist in the db.
    http_response_code(404);
    echo json_encode(
        array("pois"=>false, "message" => "No result")
    );
}
