<?php

/**
 * @package GaragesApi
 */

namespace Inc\Controllers;

use Inc\Models\GaragesModel;

class GaragesController
{

  public function __construct(private GaragesModel $getway)
  {
  }

  /*
  * Combined multiple parameters in a single API end point.
  * @API end point: /garages?owner=xyz
  * @API end point: /garages?garage_id=123456
  * @API end point: /garages?owner_id=12345
  * @API end point: /garages?country=finland
  * @API end point: /garages?long=60.168673901487510&lat=24.930162952045407&radius=4
  */
  public function processRequest(string $method, ?string $queryString): void
  {

    if ($queryString) {
      //Filter Garage Info. Based On Owner, Country, location 
      $this->processResourceRequest($method, $queryString);
    } else {
      // Read all the products.
      $this->processCollectionRequest($method);
    }
  }

  private function processResourceRequest(string $method, string $queryString): void
  {

    $garages =  $this->getway->get($queryString);

    if (isset($garages['invalid_param'])) {
      // If any invalid parameter found.
      http_response_code(403);
      echo json_encode([
        "message" => $garages['invalid_param']
      ]);
      return;
    } elseif (!$garages) {
      //If garages info is removed.
      http_response_code(404); // 404 = item not found
      echo json_encode([
        "message" => "Garage not found"
      ]);
      return;
    } else {

      // Get The Data.
      // Future, We can easily add POST, PATCH, DELETE methods here and extend the functionalities.
      switch ($method) {
        case "GET":
          echo json_encode([
            "garages" => $garages
          ]);
          break;

        default:
          http_response_code(405); // 405 = method not allowed
          header("Allow: GET");
          break;
      }
    }
  }


  /*
  * Get all the garages info.
  * @API end point: /garages
  */
  private function processCollectionRequest(string $method): void
  {

    switch ($method) {

      case "GET":
        echo json_encode([
          "garages" => $this->getway->getAll()
        ]);
        break;

      default:
        http_response_code(405); // 405 = method not allowed
        header("Allow: GET");
        break;
    }
  }
}