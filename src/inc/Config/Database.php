<?php

/**
 * @package GaragesApi
 */

namespace Inc\Config;

class Database
{

  public function __construct()
  {
  }

  public function getConnection(): \PDO
  {

    $dsn = "mysql:host=" . Config::$DB_HOST . "; dbname=" . Config::$DB_NAME . "; charset=utf8";
    return new \PDO($dsn, Config::$DB_USER, Config::$DB_PASSWORD, [
      \PDO::ATTR_EMULATE_PREPARES => false,
      \PDO::ATTR_STRINGIFY_FETCHES => false
    ]);
  }
}