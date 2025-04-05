<?php

namespace App\Interfaces\Services;

use App\Models\TravelOrder;

interface TravelOrderServiceInterface
{
    public function createTravelOrder(array $data): TravelOrder;
}
