<?php

namespace App\Repositories\Contracts;

interface ProductTransferRepositoryInterface
{
    public function getTransferStatuses($locationId, $locationType);

}
