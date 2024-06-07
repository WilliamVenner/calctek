<?php

namespace App\Http\Controllers\CalcController\Parser\FunctionToken;

class PiFunctionToken extends FunctionToken {
    public const NAME = 'pi';

    public int $args = 0;

    public function evaluate($args) {
        return pi();
    }
}
