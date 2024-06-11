<?php

namespace App\Http\Controllers\CalcController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CalcController\Lexer\Lexer;
use App\Http\Controllers\CalcController\Parser\Parser;
use App\Models\CalcHistoryEntry;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalcController extends Controller
{
    public function index(Request $request) {
        return Inertia::render('Calculator', [
            'calc_history' => $request->user()?->calc_entries ?? [],
        ]);
    }

    public function eval(Request $request, string $expr)
    {
        try {
            $tokens = (new Lexer)->lex($expr);
            $evaluator = (new Parser($tokens))->infix_to_rpn($tokens);
            $result = $evaluator->evaluate();
            $result = strval($result);

            if ($user = $request->user()) {
                $entry = new CalcHistoryEntry();
                $entry->input_expression = $expr;
                $entry->output = $result;
                $entry->user_id = $user->id;
                $entry->save();
            }

            return response($result, 200);
        } catch (CalcException $e) {
            return response($e->getMessage(), $e->httpCode);
        }
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
