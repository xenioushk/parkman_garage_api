<?php

/**
 * @package GaragesApi
 */

namespace Inc\Config;

class Config
{

  // Database Settings.
  public static $DB_HOST = "mysql";
  public static $DB_NAME = "garage_db";
  public static $DB_USER = "root";
  public static $DB_PASSWORD = "secret";

  // Table Settings.
  public static $Table_Garage = "garage";
  public static $Table_Garage_Info = "garage_info";
  public static $Table_Garage_Owner = "garage_owner";
}