<?php

namespace App\Http\Controllers\CalcController\Parser\Operation;

use App\Http\Controllers\CalcController\NamedCalcClass;

/**
 * Operations that can be performed on numbers by the Evaluator.
 */
abstract class Operation {
    use NamedCalcClass;
}
