<?php

namespace App\Http\Controllers\CalcController\Parser\Operation;

/**
 * Interface for prefix unary operations (operations that take one operand from their right side)
 *
 * For example, the negation operation in "-5" is a prefix unary operation.
 */
interface PrefixUnaryOperation extends UnaryOperation {}
