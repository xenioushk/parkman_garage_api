<?php

declare(strict_types=1);

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
  require_once dirname(__FILE__) . '/vendor/autoload.php';
}

require_once "inc/Config/ErrorHandler.php";

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleEexception");

use Inc\Config\Database;
use Inc\Models\ProductGatewayModel;
use Inc\Controllers\ProductController;

// return all the response in json format.
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if ($parts[1] != "products" && $parts[1] != "garages") {
  http_response_code(404);
  exit;
}

$id = $parts[2] ?? null;

$database = new Database("mysql", "product_db", "root", "secret");

$routing = $parts[1];

switch ($routing) {

  case "products":

    // Send DB connection info.
    $gateway = new ProductGatewayModel($database);

    // Create instance of controller class.
    $contoller = new ProductController($gateway);

    // Process Request.
    $contoller->processRequest($_SERVER["REQUEST_METHOD"], $id);
    break;

  case "garages":

    echo "wow";
    break;

  default:
    break;
}