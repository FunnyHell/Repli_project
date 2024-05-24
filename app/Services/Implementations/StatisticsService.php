<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductTransferRepositoryInterface;
use App\Services\Contracts\StatisticsServiceInterface;

class StatisticsService implements StatisticsServiceInterface
{

    protected ProductTransferRepositoryInterface $productTransferRepository;
    protected OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository, ProductTransferRepositoryInterface $productTransferRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->productTransferRepository = $productTransferRepository;
    }

    public function getSalesData($locationId, $locationType, $startDate, $endDate)
    {
        return $this->orderRepository->getSalesData($locationId, $locationType, $startDate, $endDate);
    }

    public function getRefundsData($locationId, $locationType, $startDate, $endDate)
    {
        return $this->orderRepository->getRefundsData($locationId, $locationType, $startDate, $endDate);
    }

    public function getTransferStatuses($locationId, $locationType)
    {
        return $this->productTransferRepository->getTransferStatuses($locationId, $locationType);
    }

}
