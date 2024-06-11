<?php

namespace App\Http\Controllers\CalcController\Parser\Operation;

/**
 * Interface for postfix unary operations (operations that take one operand from their left side)
 *
 * For example, the factorial operation in "5!" is a postfix unary operation.
 */
interface PostfixUnaryOperation extends UnaryOperation {}
