<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;

class CheckPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $permission
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission): Response|RedirectResponse
    {
        try {
            $modulePermissions = (Session::get('permission'))->toArray();
            $otherPermissions = (Session::get('other_permission'))->toArray();

            if (!is_array($modulePermissions) || !is_array($otherPermissions)) {
                throw new \RuntimeException('Invalid permissions');
            }

            $permissions = array_merge($modulePermissions, $otherPermissions);
            if (!isset($permissions[$permission]) || (int) $permissions[$permission] !== 1) {
                throw new \RuntimeException('Insufficient permission');
            }
            return $next($request);
        } catch (\Exception $e) {
            // Handle the exception, such as redirecting to an error page or returning a custom response
            abort(403); // or redirect to an error page
        }
    }
}
