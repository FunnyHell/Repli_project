<?php

namespace App\Repositories\Implementations;

use App\Models\ProductTransfer;
use App\Repositories\Contracts\ProductTransferRepositoryInterface;

class ProductTransferRepository implements ProductTransferRepositoryInterface
{

    public function getTransferStatuses($locationId, $locationType)
    {
        $transfers = ProductTransfer::with('products')
            ->where(function($query) use ($locationId, $locationType) {
                $query->where('from_location_id', $locationId)
                    ->where('from_location_type', $locationType)
                    ->orWhere(function($query) use ($locationId, $locationType) {
                        $query->where('to_location_id', $locationId)
                            ->where('to_location_type', $locationType);
                    });
            })
            ->select('id', 'status', 'transfer_date', 'product_id')
            ->get();

        $statusCounts = $transfers->groupBy('status')->map(function ($items, $key) {
            return [
                'status' => $key,
                'total' => $items->count(),
                'items' => $items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'status' => $item->status,
                        'transfer_date' => $item->transfer_date,
                        'product' => [
                            'id' => $item->products->id,
                            'name' => $item->products->name,
                        ], // Обработка случая, когда продукт отсутствует
                    ];
                }),
            ];
        });

        // Ensure all columns are present, even if empty
        $allStatuses = ['pending', 'approved', 'in progress', 'delivered', 'rejected'];
        foreach ($allStatuses as $status) {
            if (!isset($statusCounts[$status])) {
                $statusCounts[$status] = [
                    'status' => $status,
                    'total' => 0,
                    'items' => collect(),
                ];
            }
        }

        return $statusCounts->values();
    }

}
