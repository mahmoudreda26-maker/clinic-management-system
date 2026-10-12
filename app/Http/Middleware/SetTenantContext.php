<?php

namespace App\Http\Middleware;

use App\Support\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContext
{
    public function __construct(
        private readonly TenantContext $tenantContext
    ) {}

    public function handle(
        Request $request,
        Closure $next
    ): Response {
        // Get the authenticated user
        $user = $request->user();

        // Reject unauthenticated requests
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // Every regular account must belong to a clinic
        if (!$user->clinic_id) {
            return response()->json([
                'success' => false,
                'message' => 'No clinic assigned to this account.',
            ], 403);
        }

        // Set the current tenant using the trusted user record
        $this->tenantContext->setClinicId(
            (string) $user->clinic_id
        );

        // Continue to the next middleware or controller
        return $next($request);
    }
}
