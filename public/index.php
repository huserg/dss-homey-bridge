<?php
require "../bootstrap.php";
use Src\Controller\DSSController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// all of our endpoints start with /dss
// everything else results in a 404 Not Found
if ($uri[1] == "") {
    header("HTTP/1.1 200 OK");
    echo("DssHomeyBridge Online");
    exit();
}
else if ($uri[1] !== 'dss') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// the event id must be a number:
$userEventId = (int) $uri[2];

$dssController = new DSSController();
echo $dssController->RaiseEvent($userEventId);
