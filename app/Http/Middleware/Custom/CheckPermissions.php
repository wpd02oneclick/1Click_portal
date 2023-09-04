<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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
            $currentUser = Auth::guard('Authorized')->user();

            // Retrieve module_permissions from cache or database
            $modulePermissions = Cache::get('module_permissions');
            if ($modulePermissions === null) {
                $modulePermissionsCollection = DB::table('module_permissions')
                ->where('Role_ID', $currentUser->Role_ID)
                    ->get();

                // Check if the collection has at least one item
                if ($modulePermissionsCollection->isNotEmpty()) {
                    // Access the first item in the collection (assuming it's a single result)
                    $modulePermissions = $modulePermissionsCollection->first();
                } else {
                    // Set a default permission if there are no results
                    $modulePermissions = (object)[
                        'Research_list' => 0,
                        // Add other properties as needed
                    ];
                }

                // Cache the module_permissions
                Cache::forever('module_permissions', $modulePermissions);
            }

            // Retrieve other_permissions from cache or database
            $otherPermissions = Cache::get('other_permissions');
            if ($otherPermissions === null) {
                $otherPermissionsCollection = DB::table('other_permissions')
                ->where('Role_ID', $currentUser->Role_ID)
                    ->first();

                // Check if the other permission exists in the database
                if ($otherPermissionsCollection !== null) {
                    // Cache the other permission object
                    Cache::forever('other_permissions', $otherPermissionsCollection);
                    $otherPermissions = $otherPermissionsCollection;
                } else {
                    // If no other permissions found, set a default other permission object
                    $otherPermissions = (object)[
                        'Research_list' => 0,
                        // Add other properties as needed
                    ];
                }
            }

            // Convert modulePermissions and otherPermissions to arrays
            $modulePermissions = (array) $modulePermissions;
            $otherPermissions = (array) $otherPermissions;

            if (!is_array($modulePermissions) || !is_array($otherPermissions)) {
                throw new \RuntimeException('Invalid permissions');
            }

            // Merge modulePermissions and otherPermissions into a single permissions array
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
