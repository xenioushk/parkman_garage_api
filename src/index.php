<?php

/**
 * @package GaragesApi
 */

declare(strict_types=1);

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
  require_once dirname(__FILE__) . '/vendor/autoload.php';
}

require_once "inc/Config/ErrorHandler.php";

// Set Error handlers output.
// All the output in json format.
set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleEexception");

use Inc\Config\Database;
use Inc\Models\GaragesModel;
use Inc\Controllers\GaragesController;

// return all the response in json format.
header("Content-type: application/json; charset=UTF-8");

// Initialize URL to the variable
$requestUrl = $_SERVER["REQUEST_URI"];

$parts = explode("/", $requestUrl);

$isValidRequest = str_contains($parts[1], 'garages');

if ($isValidRequest !== true) {
  http_response_code(404);
  exit;
}

//Check and parse query string.

$queryString = "";

if (count($_GET) > 0) {

  $queryString = explode('?', $parts[1])[1];
}

// Connect To Database.
$database = new Database();

// Initiate Model.
$gateway = new GaragesModel($database);

// Create instance of controller class.
$contoller = new GaragesController($gateway);

// Process Request.
$contoller->processRequest($_SERVER["REQUEST_METHOD"], $queryString);