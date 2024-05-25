<?php

namespace App\Repositories\Implementations;

use App\Models\ProductTransfer;
use App\Repositories\Contracts\ProductTransferRepositoryInterface;

class ProductTransferRepository implements ProductTransferRepositoryInterface
{

    public function getTransferStatuses($locationId, $locationType)
    {
        $oneMonthAgo = now()->subMonth(); // Получаем дату месяц назад

        $transfers = ProductTransfer::with('products')
            ->where(function($query) use ($locationId, $locationType) {
                $query->where('from_location_id', $locationId)
                    ->where('from_location_type', $locationType)
                    ->orWhere(function($query) use ($locationId, $locationType) {
                        $query->where('to_location_id', $locationId)
                            ->where('to_location_type', $locationType);
                    });
            })
            ->where(function($query) use ($oneMonthAgo) {
                $query->where('transfer_date', '>', $oneMonthAgo)
                    ->orWhere(function($query) {
                        $query->where('status', '!=', 'delivered')
                            ->where('status', '!=', 'rejected');
                    });
            })
            ->select('id', 'status', 'transfer_date', 'product_id', 'updated_at')
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
                        ],
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
