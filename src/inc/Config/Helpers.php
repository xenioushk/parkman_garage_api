<?php

/**
 * @package GaragesApi
 */

namespace Inc\Config;

class Helpers
{

  /*
  * Check and filter all the parameters in Get request.
  * @return array
  */

  public static function checkColumnValidity(string $columnName, ?string $value): array
  {

    $validColumnLists = [
      'garage_id' => [
        'query' => " AND GR.garage_id=" . trim($value)
      ],
      'owner_id' => [
        'query' => " AND GR.owner_id=" . trim($value)
      ],
      'owner' => [
        'query' => " AND GO.owner_name LIKE '%" . trim($value) . "%'"
      ],
      'country' => [
        'query' => " AND GR.country LIKE '%" . trim($value) . "%'"
      ],

      'lat' => [
        'query' => 'lat'
      ],

      'long' => [
        'query' => 'long'
      ],

      'radius' => [
        'query' => 'radius'
      ]
    ];


    if (array_key_exists($columnName, $validColumnLists)) {
      return $validColumnLists[$columnName];
    }

    return [
      "error" => "$columnName is invalid parameter!"
    ];
  }
}