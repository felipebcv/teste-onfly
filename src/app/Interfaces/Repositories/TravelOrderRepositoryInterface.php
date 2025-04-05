<?php

namespace App\Interfaces\Repositories;

use App\Models\TravelOrder;

interface TravelOrderRepositoryInterface
{
    public function create(array $data): TravelOrder;
    
}
