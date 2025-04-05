<?php

namespace App\Services;

use App\Interfaces\Repositories\TravelOrderRepositoryInterface;
use App\Interfaces\Services\TravelOrderServiceInterface;
use App\Models\TravelOrder;


class TravelOrderService implements TravelOrderServiceInterface
{
    protected $travelOrderRepository;

    public function __construct(TravelOrderRepositoryInterface $travelOrderRepository)
    {
        $this->travelOrderRepository = $travelOrderRepository;
    }

    public function createTravelOrder(array $data): TravelOrder
    {
        $data['status_id'] = 1;

        if (!isset($data['user_id']) && auth()->check()) {
            $data['user_id'] = auth()->id();
        }

        return $this->travelOrderRepository->create($data);
    }
}
