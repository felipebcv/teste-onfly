<?php

namespace App\Repositories;

use App\Interfaces\Repositories\TravelOrderRepositoryInterface;
use App\Models\TravelOrder;

class TravelOrderRepository implements TravelOrderRepositoryInterface
{
    public function create(array $data): TravelOrder
    {
        return TravelOrder::create($data);
    }

}
