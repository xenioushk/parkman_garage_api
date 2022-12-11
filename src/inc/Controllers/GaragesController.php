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

  public function processRequest(string $method, ?string $id): void
  {

    if ($id) {
      //Read a single garages details.
      $this->processResourceRequest($method, $id);
    } else {
      // Read all the products.
      $this->processCollectionRequest($method);
    }
  }

  private function processResourceRequest(string $method, string $id): void
  {

    $garages =  $this->getway->get($id);

    if (!$garages) {
      http_response_code(404); // 404 = item not found
      echo json_encode([
        "message" => "Garage not found"
      ]);
      return;
    }

    switch ($method) {
      case "GET":
        echo json_encode($garages);
        break;

      case "PATCH":
        // convert all the response to array.
        $data = (array) json_decode(file_get_contents("php://input"), true);

        // Validate data.

        $errors = $this->getValidationErrors($data, false);

        if (!empty($errors)) {

          http_response_code(422); // error code for unprocessable entities.
          echo json_encode([
            "errors" => $errors
          ]);
          break;
        }

        // Update value.
        $rows = $this->getway->update($garages, $data);

        http_response_code(200);
        echo json_encode([
          "message" => "Garage $id updated.",
          "rows" => $rows
        ]);

        break;

      case "DELETE":
        $rows = $this->getway->delete($id);
        echo json_encode([
          "message" => "Garage $id deleted.",
          "rows" => $rows
        ]);
        break;

      default:
        http_response_code(405); // 405 = method not allowed
        header("Allow: GET, PATCH, DELETE");
        break;
    }
  }

  private function processCollectionRequest(string $method): void
  {

    switch ($method) {

      case "GET":
        echo json_encode([
          "garages" => $this->getway->getAll()
        ]);
        break;

      case "POST":

        // Convert all the response to array.
        $data = (array) json_decode(file_get_contents("php://input"), true);

        // Validate data.

        $errors = $this->getValidationErrors($data);

        if (!empty($errors)) {

          http_response_code(422); // error code for unprocessable entities.
          echo json_encode([
            "errors" => $errors
          ]);
          break;
        }

        // Create a new post.
        $id = $this->getway->create($data);

        // Bind a response code.

        http_response_code(201); // 201= A new item created.

        echo json_encode([
          'message' => "Garage created.",
          'id' => $id
        ]);

        break;

      default:
        http_response_code(405); // 405 = method not allowed
        header("Allow: GET, POST");
        break;
    }
  }

  private function getValidationErrors(array $data, bool $is_new = true): array
  {
    $errors = [];

    if ($is_new && empty($data["name"])) {
      $errors[] = "name is required.";
    }

    if (array_key_exists("size", $data)) {

      if (filter_var($data["size"], FILTER_VALIDATE_INT) === false) {
        $errors[] = "size must be integer";
      }
    }

    return $errors;
  }
}