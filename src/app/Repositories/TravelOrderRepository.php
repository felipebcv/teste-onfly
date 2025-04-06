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

    public function update(TravelOrder $travelOrder, array $data): TravelOrder
    {
        $travelOrder->fill($data);
        $travelOrder->save();
        return $travelOrder;
    }

    public function findTravelOrder(int $id): ?TravelOrder
    {
        return TravelOrder::where('id', $id)->first();
    }

}
