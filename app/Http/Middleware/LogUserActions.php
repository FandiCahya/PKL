<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogUserActions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Ambil informasi yang relevan dari request dan response
        $userId = Auth::id();
        $method = $request->method();
        $path = $request->getPathInfo();

        // Cek jika request adalah CRUD (Create, Read, Update, Delete)
        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $action = $method;
        } else {
            $action = 'READ'; // Jika bukan CRUD, anggap sebagai baca ("READ")
        }

        // Tulis log ke dalam tabel logs
        Log::info("User with ID {$userId} {$action} on {$path}");

        return $response;
    }
}
