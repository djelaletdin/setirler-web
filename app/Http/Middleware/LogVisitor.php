<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Visitor;
use Jenssegers\Agent\Agent;

class LogVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = Auth::check() ? Auth::id() : null;
        $ipAddress = $request->ip();

        $agent = new Agent();
        $agent->setUserAgent($request->header('User-Agent'));

        $device = $agent->device();
        $operatingSystem = $agent->platform();

        // check the latest visit
        $recentVisit = Visitor::where('ip_address', $ipAddress)
            ->where('user_id', $userId)
            ->where('visited_at', '>=', now()->subMinutes(5))
            ->exists();

        if (!$recentVisit) {
            Visitor::create([
                'ip_address' => $ipAddress,
                'user_id' => $userId,
                'visited_at' => now(),
                'device' => $device,
                'operating_system' => $operatingSystem,
            ]);
        }

        return $next($request);
    }
}
