<?php

namespace App\Http\Middleware;

use App\Enums\HttpStatusCodes;
use App\Enums\Permissions;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (! $request->apiKey->permissions) {
            return response()->json([
                'message' => 'Permission Denied'
            ], HttpStatusCodes::FORBIDDEN);
        }

        $validPermission = $request->apiKey->permissions;
        if ($validPermission === Permissions::SUPER_ADMIN || $validPermission === Permissions::EDITOR || $validPermission === Permissions::VIEWER) {
            return $next($request);
        }

        // if (! is_array($request->apiKey['permissions'])) {
        //     $permissions = explode(',', $request->apiKey['permissions']);
        // } else {
        //     $permissions = $request->apiKey['permissions'];
        // }

        // $validPermission = in_array($request->apiKey['permissions'], $permissions);

        return response()->json([
            'message' => 'Permission Denied'
        ], HttpStatusCodes::FORBIDDEN);

    }
}
