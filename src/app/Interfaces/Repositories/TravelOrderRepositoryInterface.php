<?php

namespace App\Interfaces\Repositories;

use App\Models\TravelOrder;

interface TravelOrderRepositoryInterface
{
    public function create(array $data): TravelOrder;
    public function findTravelOrder(int $id): ?TravelOrder;
    public function update(TravelOrder $travelOrder, array $data): TravelOrder;    
    
}
