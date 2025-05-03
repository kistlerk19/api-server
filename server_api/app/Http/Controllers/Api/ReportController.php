<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Services\QueryService;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    protected $queryService;

    public function __construct(QueryService $queryService)
    {
        $this->queryService = $queryService;
    }

    // Get top customers by spending
    public function topCustomersBySpending()
    {
        $customers = $this->queryService->getTopCustomersBySpending();

        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }

    // Get monthly sales report (only shipped/delivered)
    public function monthlySalesReport()
    {
        $report = $this->queryService->getMonthlySalesReport();

        return response()->json([
            'success' => true,
            'data' => $report,
        ]);
    }

    // Get products that were never ordered
    public function productsNeverOrdered()
    {
        $products = $this->queryService->getProductsNeverOrdered();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    // Get average order value by country
    public function averageOrderValueByCountry()
    {
        $averageValues = $this->queryService->getAverageOrderValueByCountry();

        return response()->json([
            'success' => true,
            'data' => $averageValues,
        ]);
    }

    // Get frequent buyers (buyers with more than one order)
    public function frequentBuyers()
    {
        $buyers = $this->queryService->getFrequentBuyers();

        return response()->json([
            'success' => true,
            'data' => $buyers,
        ]);
    }
}
