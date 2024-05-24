<?php

namespace App\Services\Contracts;

interface StatisticsServiceInterface
{
    public function getSalesData($locationId, $locationType, $startDate, $endDate);

    public function getRefundsData($locationId, $locationType, $startDate, $endDate);


    public function getTransferStatuses($locationId, $locationType);
}
