<?php

namespace App\Http\Middleware;

use Closure;
use ReflectionClass;
use Illuminate\Http\Request;

class EnsureModelIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        foreach (getClassesList(app_path('Models')) as $class) {
            $reflection = new ReflectionClass($class);
            if ($reflection->hasMethod('scopeActive')) {
                $class::addGlobalScope('is_active', fn ($q) => $q->active());
            }
        }

        return $next($request);
    }
}
