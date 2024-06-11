<?php

namespace Tests;

use Tests\ExactTestResponse;
use Tests\TestCase;

/**
 * An extension of the TestCase class that returns a ExactTestResponse object instead of a TestResponse object when making GET requests.
 */
class ExactTestCase extends TestCase {
    public function get($uri, array $headers = [])
    {
        return new ExactTestResponse(parent::get($uri, $headers));
    }
}
