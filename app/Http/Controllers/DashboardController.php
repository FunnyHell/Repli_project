<?php

namespace App\Http\Controllers;

use App\Services\Implementations\StatisticsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected StatisticsService $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function index(Request $request): \Inertia\Response
    {
        $locationId = auth()->user()->location_id;
        $locationType = auth()->user()->location_type;

        $startDate = $request->input('startDate', now()->subMonth()->toDateString());
        $endDate = $request->input('endDate', now()->toDateString());

        $salesData = $this->statisticsService->getSalesData($locationId, $locationType, $startDate, $endDate)->toArray();
        $refundsData = $this->statisticsService->getRefundsData($locationId, $locationType, $startDate, $endDate)->toArray();
        $transferStatuses = $this->statisticsService->getTransferStatuses($locationId, $locationType)->toArray();

        return Inertia::render('Dashboard', [
            'salesData' => $salesData,
            'refundsData' => $refundsData,
            'transferStatuses' => $transferStatuses,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
