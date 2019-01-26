<?php
// show error reporting
error_reporting(E_ALL);

// set your default time-zone
date_default_timezone_set('Europe/Athens');

// variables used for jwt
$key = "example_key";
$iss = "http://example.org";
$aud = "http://example.com";
$iat = 1356999524;
$nbf = 1357000000;

// variables used for pagination
$url    = $_SERVER['HTTP_HOST']."/api/";
$page   = isset($_GET['page']) ? $_GET['page'] : 1;
$limit  = 5;
$offset = ($limit * $page) - $limit;