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

    public function findTravelOrder(int $id): ?TravelOrder
    {
        return $this->travelOrderRepository->findTravelOrder($id);
    }

    public function updateStatus(TravelOrder $travelOrder, int $newStatusId): TravelOrder
    {
        $this->travelOrderRepository->update($travelOrder, ['status_id' => $newStatusId]);
        
        return $travelOrder->fresh();
    }

    public function cancelTravelOrder(TravelOrder $travelOrder): TravelOrder
    {
        $this->travelOrderRepository->update($travelOrder, ['status_id' => 3]); 
        
        return $travelOrder->fresh();
    }
}
