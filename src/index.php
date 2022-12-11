<?php

declare(strict_types=1);

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
  require_once dirname(__FILE__) . '/vendor/autoload.php';
}

require_once "inc/Config/ErrorHandler.php";

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleEexception");

use Inc\Config\Database;
use Inc\Models\GaragesModel;
use Inc\Controllers\GaragesController;

// return all the response in json format.
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

echo "<pre>";
print_r($_SERVER["REQUEST_URI"]);
echo "</pre>";

if ($parts[1] != "garages") {
  http_response_code(404);
  exit;
}

$id = $parts[2] ?? null;

$database = new Database();

// Initiate Model.
$gateway = new GaragesModel($database);

// Create instance of controller class.
$contoller = new GaragesController($gateway);

// Process Request.
$contoller->processRequest($_SERVER["REQUEST_METHOD"], $id);