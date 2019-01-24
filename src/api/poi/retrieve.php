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