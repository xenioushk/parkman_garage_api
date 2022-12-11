<?php

/**
 * @package GaragesApi
 */

namespace Inc\Models;

use Inc\Controllers\BaseController;
use Inc\Config\Database;
use PDO;

class ProductGatewayModel  extends BaseController
{

  private \PDO $conn;

  public function __construct(Database $database)
  {
    $this->conn = $database->getConnection();
  }

  // Read all the products.

  public function getAll(): array
  {

    $sql = "SELECT * 
                FROM " . BaseController::TableProduct;

    $stmt = $this->conn->query($sql);

    $data = [];

    // return an associative array.
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

      $row["is_available"] = (bool) $row["is_available"];
      $data[] = $row;
    }

    return $data;
  }

  // Create a new product.
  public function create(array $data): string
  {
    $sql = "INSERT INTO " . BaseController::TableProduct .
      " (name, size, is_available) VALUES (:name, :size, :is_available)";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":name", $data['name'], \PDO::PARAM_STR);
    $stmt->bindValue(":size", $data['size'] ?? 0, \PDO::PARAM_INT);
    $stmt->bindValue(":is_available", (bool) $data['is_available'] ?? false, \PDO::PARAM_BOOL);

    $stmt->execute();

    return $this->conn->lastInsertId(); // return the last inserted id.
  }

  // get an item information.

  public function get(string $id): array | false
  {

    $sql = "SELECT * FROM " . BaseController::TableProduct . " WHERE id=:id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":id", $id, \PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($data !== false) {
      $data["is_available"] = (bool) $data["is_available"];
    }
    return $data;
  }

  // update.

  public function update(array $current, array $new): int
  {

    $sql = "UPDATE product SET name = :name, size= :size, is_available= :is_available WHERE id=:id";
    $stmt = $this->conn->prepare($sql);

    $stmt->bindValue(":name", $new["name"] ?? $current["name"], \PDO::PARAM_STR);
    $stmt->bindValue(":size", $new["size"] ?? $current["size"], \PDO::PARAM_INT);
    $stmt->bindValue(":is_available", $new["is_available"] ?? $current["is_available"], \PDO::PARAM_BOOL);
    $stmt->bindValue(":id", $current["id"], \PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->rowCount();
  }
  // delete.

  public function delete(int $id): int
  {

    $sql = "DELETE FROM product WHERE id=:id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":id", $id, \PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->rowCount();
  }
}