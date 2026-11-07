<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            DB::select('select 1');
            $db = 'ok';
        } catch (Throwable $e) {
            report($e);
            $db = 'down';
        }

        return response()->json(
            ['app' => 'ok', 'database' => $db],
            $db === 'ok' ? 200 : 503,
            ['Cache-Control' => 'no-store'],
        );
    }
}
