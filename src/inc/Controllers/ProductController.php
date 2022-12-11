<?php

/**
 * @package PMApiPlugin
 */

namespace Inc\Controllers;

use Inc\Models\ProductGatewayModel;

class ProductController extends BaseController
{

  public function __construct(private ProductGatewayModel $getway)
  {
  }

  public function processRequest(string $method, ?string $id): void
  {
    if ($id) {
      $this->processResourceRequest($method, $id);
    } else {
      $this->processCollectionRequest($method);
    }
  }

  private function processResourceRequest(string $method, string $id): void
  {
    var_dump($method, $id);
  }

  private function processCollectionRequest(string $method): void
  {

    switch ($method) {

      case "GET":
        echo json_encode($this->getway->getAll());
        break;
    }
  }
}