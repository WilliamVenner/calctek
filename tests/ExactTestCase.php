<?php

namespace Tests;

use Tests\ExactTestResponse;
use Tests\TestCase;

class ExactTestCase extends TestCase {
    public function get($uri, array $headers = [])
    {
        return new ExactTestResponse(parent::get($uri, $headers));
    }
}
