<?php

/**
 * @package PMApiPlugin
 */


class ErrorHandler

{
  public static function handleEexception(Throwable $exception): void

  {
    http_response_code(500); // server error.

    echo json_encode([
      "code" => $exception->getCode(),
      "message" => $exception->getMessage(),
      "file" => $exception->getFile(),
      "line" => $exception->getLine()
    ]);
  }
}