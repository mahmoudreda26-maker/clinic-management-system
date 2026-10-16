<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * السماح بالدخول للأدوار المحددة فقط.
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        // جلب المستخدم الحالي بعد Authentication.
        $user = $request->user();

        // التأكد من أن المستخدم مسجل دخوله.
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // التأكد من أن Role المستخدم مسموح به.
        if (!in_array($user->role, $roles, true)) {
            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية للوصول إلى هذا المورد.',
            ], 403);
        }

        // السماح باستكمال الطلب إذا كان الـ Role مسموحًا.
        return $next($request);
    }
}
