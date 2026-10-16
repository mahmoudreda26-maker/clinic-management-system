
<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login');

    Route::middleware(['auth:sanctum', 'tenant'])->group(function () {

        Route::get('/me', [AuthController::class, 'me']);

        Route::post('/logout', [AuthController::class, 'logout']);
    });
});




Route::middleware(['auth:sanctum', 'tenant'])
    ->prefix('patients')
    ->controller(PatientController::class)
    ->group(function () {

        Route::middleware('role:owner,receptionist,doctor,nurse')
            ->group(function () {
                Route::get('/', 'index');
                Route::get('show/{patientId}', 'show');
            });
        Route::middleware('role:owner,receptionist')
            ->group(function () {
                Route::post('/', 'store');
                Route::put('/{patientId}', 'update');
                Route::patch('/{patientId}', 'update');
                Route::delete('/{patientId}', 'destroy');
            });
    });
