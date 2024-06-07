<?php

// patientprism/patientprism-interview

namespace App\Http\Controllers\CalcController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CalcController\Lexer\Lexer;
use App\Http\Controllers\CalcController\Parser\Parser;

class CalcController extends Controller
{
    public function eval(string $expr)
    {
        $tokens = (new Lexer)->lex($expr);
        $evaluator = (new Parser($tokens))->infix_to_rpn($tokens);
        $result = $evaluator->evaluate();
        return response(strval($result), 200);
    }

    public function add($a, $b)
    {
        if (!is_numeric($a) || !is_numeric($b))
            return response('', 400);

        return $a + $b;
    }

    public function sub($a, $b)
    {
        if (!is_numeric($a) || !is_numeric($b))
            return response('', 400);

        return $a - $b;
    }

    public function mul($a, $b)
    {
        if (!is_numeric($a) || !is_numeric($b))
            return response('', 400);

        return $a * $b;
    }

    public function div($a, $b)
    {
        if (!is_numeric($a) || !is_numeric($b))
            return response('', 400);

        return $a / $b;
    }
}
