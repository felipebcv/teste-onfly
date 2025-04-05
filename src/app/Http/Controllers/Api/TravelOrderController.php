<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTravelOrderRequest;
use App\Interfaces\Services\TravelOrderServiceInterface;
use Illuminate\Http\Request;
use App\Models\TravelOrder;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Travel Orders API",
 *     description="API for managing travel orders"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Bearer"
 * )
 */


class TravelOrderController extends Controller
{
    protected $travelOrderService;

    public function __construct(TravelOrderServiceInterface $travelOrderService)
    {
        $this->travelOrderService = $travelOrderService;
    }

    /**
     * @OA\Post(
     *     path="/api/travel-orders",
     *     summary="Create a new travel order",
     *     operationId="createTravelOrder",
     *     tags={"TravelOrders"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Travel order data",
     *         @OA\JsonContent(
     *             required={"destination_id", "departure_date", "return_date"},
     *             @OA\Property(property="destination_id", type="integer", example=3, description="ID of the destination"),
     *             @OA\Property(property="departure_date", type="string", format="date", example="2025-05-01", description="Date of departure"),
     *             @OA\Property(property="return_date", type="string", format="date", example="2025-05-10", description="Date of return")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Travel order created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TravelOrder")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreTravelOrderRequest $request): JsonResponse
    {
        $data = $request->validated();
        $travelOrder = $this->travelOrderService->createTravelOrder($data);
        return response()->json($travelOrder, 201);
    }

}
