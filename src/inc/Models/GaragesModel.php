<?php

/**
 * @package GaragesApi
 */

namespace Inc\Models;

use Inc\Config\Helpers;
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
                  GI.owner_id = GO.owner_id";

    $stmt = $this->conn->query($sql);

    $data = [];

    // return an associative array.
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

      $data[] = $row;
    }

    return $data;
  }

  // Read Filtered garage info.

  public function get(string $queryString): array | false
  {

    // Initalization.
    $data = [];
    $latitude = 0;
    $longitude = 0;
    $radius = 5; // lists all the items within 5miles from provided latitute and longitude.   
    $radiusCondition = "";
    $whereCondition = "";

    // Default unit is miles. 
    // If you want to change it to kilometers, change the value 3959 to 6371

    $havingCondition = "(
            3959 * acos (
            cos ( radians(%lat%) )
            * cos( radians( GR.latitude ) )
            * cos( radians( GR.longitude ) - radians(%long%) )
            + sin ( radians(%lat%) )
            * sin( radians( GR.latitude ) )
          )
      ) AS distance";



    $queryString = explode("&", $queryString);

    $parametersValidity = [];

    foreach ($queryString as $query) {

      $query = explode("=", $query);

      if (isset($query[0]) && isset($query[1])) {

        $columnInfo = Helpers::checkColumnValidity($query[0], $query[1]);

        if (isset($columnInfo['query'])) {

          if ($columnInfo['query'] === "lat") {
            $latitude = trim($query[1]);
            $havingCondition = str_replace("%lat%", $latitude, $havingCondition);
          } else if ($columnInfo['query'] === "long") {
            $longitude = trim($query[1]);
            $havingCondition = str_replace("%long%", $longitude, $havingCondition);
          } else if ($columnInfo['query'] === "radius") {
            $radius = $query[1];
          } else {
            $whereCondition .= " " . $columnInfo['query'] . " ";
          }
        } else {
          $parametersValidity[] = $columnInfo['error'];
        }
      }
    }


    // Return, if any invalid parameter found.
    if (!empty($parametersValidity)) {
      $data['invalid_param'] = $parametersValidity;
      return $data;
    }

    // To calculate distance, we need both latitude, longitude value. Radius is optional. Default: 5 miles.
    if (!empty($havingCondition) && $latitude !== 0 && $longitude !== 0) {
      $havingCondition = ", $havingCondition";
      $radiusCondition = " HAVING distance <= $radius";
    } else {
      $havingCondition = "";
    }

    // Generate SQL.
    $sql = "SELECT 
                  GR.garage_id, GR.garage_name AS name, GR.hourly_price, GR.currency, 
                  GO.owner_email AS contact_email, 
                   CONCAT_WS(' ', GR.latitude, GR.longitude) as point, GR.country,
                  GO.owner_id, GO.owner_name AS garage_owner 
                  $havingCondition 
                FROM 
                  garage AS GR, garage_info AS GI, garage_owner AS GO 
                WHERE 1 AND
                  GI.garage_id=GR.garage_id AND
                  GI.owner_id = GO.owner_id 
                  $whereCondition
                  $radiusCondition
                ";

    $stmt = $this->conn->query($sql);

    // Return an associative array.
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {

      // comment it, if you want to display the distance in json output.
      unset($row['distance']);
      $data[] = $row;
    }
    return $data;
  }
}