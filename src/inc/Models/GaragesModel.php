<?php

/**
 * @package GaragesApi
 */

namespace Inc\Models;

use PDO;
use Inc\Config\Config;
use Inc\Config\Database;

class GaragesModel
{

  private \PDO $conn;

  public function __construct(Database $database)
  {
    $this->conn = $database->getConnection();
  }

  // Read all garages.

  public function getAll(): array
  {

    $sql = "SELECT 
                  GR.garage_id, GR.garage_name AS name, GR.hourly_price, GR.currency, 
                  GO.owner_email AS contact_email, 
                  CONCAT_WS(' ', GR.latitude, GR.longitude) as point, GR.country,
                  GO.owner_id, GO.owner_name AS garage_owner
                FROM 
                  garage AS GR, garage_info AS GI, garage_owner AS GO 
                WHERE 
                  GI.garage_id=GR.garage_id AND
                  GI.owner_id = GO.owner_id
                ";

    $stmt = $this->conn->query($sql);

    $data = [];

    // return an associative array.
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

      $data[] = $row;
    }

    return $data;
  }

  // Read single garage.

  public function get(string $id): array | false
  {

    $sql = "SELECT 
                  GR.garage_id, GR.garage_name AS name, GR.hourly_price, GR.currency, 
                  GO.owner_email AS contact_email, 
                  CONCAT_WS(' ', GR.latitude, GR.longitude) as point, GR.country,
                  GO.owner_id, GO.owner_name AS garage_owner
                FROM 
                  garage AS GR, garage_info AS GI, garage_owner AS GO 
                WHERE 
                  GI.garage_id=:id AND
                  GI.garage_id=GR.garage_id AND
                  GI.owner_id = GO.owner_id
                ";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":id", $id, \PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return $data;
  }

  // Create a new garage.
  public function create(array $data): string
  {
    $sql = "INSERT INTO " . Config::$Table_Garage .
      " (name, size, is_available) VALUES (:name, :size, :is_available)";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":name", $data['name'], \PDO::PARAM_STR);
    $stmt->bindValue(":size", $data['size'] ?? 0, \PDO::PARAM_INT);
    $stmt->bindValue(":is_available", (bool) $data['is_available'] ?? false, \PDO::PARAM_BOOL);

    $stmt->execute();

    return $this->conn->lastInsertId(); // return the last inserted id.
  }

  // Update a new garage.

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

  // Delete a garage.

  public function delete(int $id): int
  {

    $sql = "DELETE FROM product WHERE id=:id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":id", $id, \PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->rowCount();
  }
}