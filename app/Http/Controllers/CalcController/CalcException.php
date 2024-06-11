<?php

namespace App\Http\Controllers\CalcController;

use Exception;

/**
 * Represents an exception specific to the CalcController.
 */
class CalcException extends Exception {
    public int $httpCode;

    public function __construct(string $message, int $httpCode) {
        parent::__construct($message);
        $this->httpCode = $httpCode;
    }
}
