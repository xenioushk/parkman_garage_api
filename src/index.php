<?php

declare(strict_types=1);

// spl_autoload_register(function ($class) {
//   require __DIR__ . "/inc/$class.php";
// });

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
  require_once dirname(__FILE__) . '/vendor/autoload.php';
}

require_once "inc/Config/ErrorHandler.php";
set_exception_handler("ErrorHandler::handleEexception");

use Inc\Config\Database;
use Inc\Models\ProductGatewayModel;
use Inc\Controllers\ProductController;



// return all the response in json format.
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

// echo "<pre>";
// print_r($parts);
// echo "</pre>";

if ($parts[1] != "products") {
  http_response_code(404);
  exit;
}

$id = $parts[2] ?? null;

$database = new Database("mysql", "product_db", "root", "secret");

// $database->getConnection();

$gateway = new ProductGatewayModel($database);

$contoller = new ProductController($gateway);

$contoller->processRequest($_SERVER["REQUEST_METHOD"], $id);