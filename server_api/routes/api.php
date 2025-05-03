<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ReportController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Report API Routes
Route::prefix('reports')->group(function () {
    Route::get('/top-customers', [ReportController::class, 'topCustomersBySpending']);
    Route::get('/monthly-sales', [ReportController::class, 'monthlySalesReport']);
    Route::get('/products-never-ordered', [ReportController::class, 'productsNeverOrdered']);
    Route::get('/average-order-value', [ReportController::class, 'averageOrderValueByCountry']);
    Route::get('/frequent-buyers', [ReportController::class, 'frequentBuyers']);
});
