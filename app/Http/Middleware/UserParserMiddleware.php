<?php

namespace App\Http\Middleware;

use Closure;

class UserParserMiddleware
{
    const PARSED_METHODS = [
        'POST', 'PUT', 'PATCH'
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (in_array($request->getMethod(), self::PARSED_METHODS)) {
            $data = json_decode($request->get('users'));

            $data = array_filter($data, function ($item) {
                if (! $item->name || ! $item->phone || ! is_numeric($item->phone)) {
                    unset($item);
                    return false;
                }
                return $item;
            });

            $request->merge([
                'users' => $data
            ]);
        }

        return $next($request);
    }
}
