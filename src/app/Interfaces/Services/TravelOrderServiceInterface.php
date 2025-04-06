<?php

namespace App\Interfaces\Services;

use App\Models\TravelOrder;

interface TravelOrderServiceInterface
{
    public function createTravelOrder(array $data): TravelOrder;
    public function findTravelOrder(int $id): ?TravelOrder;
    public function updateStatus(TravelOrder $travelOrder, int $newStatusId): TravelOrder;
    public function cancelTravelOrder(TravelOrder $travelOrder): TravelOrder;
}
