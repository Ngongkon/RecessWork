<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Challenge;

class CheckChallengeDate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $challengeId = $request->route('challengeId');
        $challenge = Challenge::find($challengeId);

        if (!$challenge || now()->lt($challenge->start_date) || now()->gt($challenge->end_date)) {
            return redirect()->withErrors('Challenge is not active.');
        }
        return $next($request);
    }
}
