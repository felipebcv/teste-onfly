<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTravelOrderRequest;
use App\Http\Requests\UpdateTravelOrderStatusRequest;
use App\Interfaces\Services\TravelOrderServiceInterface;
use Illuminate\Http\Request;
use App\Models\TravelOrder;
use App\Notifications\TravelOrderStatusNotification;
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

    /**
     * @OA\Patch(
     *     path="/api/travel-orders/{id}/status",
     *     summary="Update the status of a travel order",
     *     operationId="updateTravelOrderStatus",
     *     tags={"TravelOrders"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the travel order to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Status data",
     *         @OA\JsonContent(
     *             required={"status_id"},
     *             @OA\Property(property="status_id", type="integer", example=2, description="2 = approved, 3 = cancelled")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status updated successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - the user cannot update their own travel order status"
     *     ),
     *     @OA\Response(response=404, description="Travel order not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function updateStatus(UpdateTravelOrderStatusRequest $request, $id): JsonResponse
    {

        $travelOrder = $this->travelOrderService->findTravelOrder($id);

        if (!$travelOrder) {
            return response()->json(['message' => 'Travel order not found'], 404);
        }

        if ($travelOrder->user_id === auth()->id()) {
            return response()->json(['message' => 'You cannot update the status of your own travel order'], 403);
        }

        $oldStatus = $travelOrder->status->name;

        $data = $request->validated();
        $newStatusId = $data['status_id'];

        $this->travelOrderService->updateStatus($travelOrder, $newStatusId);
        $travelOrder->refresh();

        $newStatus = $travelOrder->status->name;

        $travelOrder->user->notify(
            new TravelOrderStatusNotification($travelOrder, $oldStatus, $newStatus)
        );

        return response()->json(['message' => 'Status updated successfully'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/travel-orders/{id}",
     *     summary="Get a travel order by its ID",
     *     operationId="getTravelOrder",
     *     tags={"TravelOrders"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the travel order to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/TravelOrder")
     *     ),
     *     @OA\Response(response=404, description="Travel order not found")
     * )
     */
    public function show($id): JsonResponse
    {
        $travelOrder = $this->travelOrderService->findTravelOrder($id);

        if (!$travelOrder) {
            return response()->json(['message' => 'Travel order not found'], 404);
        }

        return response()->json($travelOrder, 200);
    }


    /**
     * @OA\Get(
     *     path="/api/travel-orders",
     *     summary="List all travel orders, optionally filtered by status",
     *     operationId="listTravelOrders",
     *     tags={"TravelOrders"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="status_id",
     *         in="query",
     *         description="Filter by travel order status ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TravelOrder")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $statusId = $request->query('status_id');

        $query = TravelOrder::query();

        if ($statusId) {
            $query->where('status_id', $statusId);
        }

        $travelOrders = $query->get();

        return response()->json($travelOrders, 200);
    }

    /**
     * @OA\Patch(
     *     path="/api/travel-orders/{id}/cancel",
     *     summary="Cancel an approved travel order, if it's still within the valid time window",
     *     operationId="cancelTravelOrder",
     *     tags={"TravelOrders"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the travel order to cancel",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order cancelled successfully"
     *     ),
     *     @OA\Response(response=400, description="Order is not approved or is already cancelled"),
     *     @OA\Response(response=403, description="Unable to cancel because return date has passed"),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    public function cancel($id)
    {
        $travelOrder = $this->travelOrderService->findTravelOrder($id);
        if (!$travelOrder) {
            return response()->json(['message' => 'Travel order not found'], 404);
        }

        if ($travelOrder->status_id !== 2) {
            return response()->json(['message' => 'Order is not approved or is already cancelled'], 400);
        }

        if (now()->greaterThan($travelOrder->return_date)) {
            return response()->json(['message' => 'Unable to cancel because return date has passed'], 403);
        }

        $oldStatus = $travelOrder->status->name;

        $this->travelOrderService->cancelTravelOrder($travelOrder);
        $travelOrder->refresh();

        $newStatus = $travelOrder->status->name;

        $travelOrder->user->notify(
            new TravelOrderStatusNotification($travelOrder, $oldStatus, $newStatus)
        );

        return response()->json(['message' => 'Order cancelled successfully'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/travel-orders/search",
     *     summary="Search travel orders by multiple criteria (status, date range, destination)",
     *     operationId="searchTravelOrders",
     *     tags={"TravelOrders"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="status_id",
     *         in="query",
     *         description="Filter by status ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Filter by departure_date >= start_date",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2025-01-01")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Filter by departure_date <= end_date",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2025-12-31")
     *     ),
     *     @OA\Parameter(
     *         name="destination_id",
     *         in="query",
     *         description="Filter by destination ID",
     *         required=false,
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TravelOrder")
     *         )
     *     )
     * )
     */
    public function search(Request $request): JsonResponse
    {
        $statusId       = $request->query('status_id');
        $startDate      = $request->query('start_date');
        $endDate        = $request->query('end_date');
        $destinationId  = $request->query('destination_id');

        $query = TravelOrder::query();

        if ($statusId) {
            $query->where('status_id', $statusId);
        }

        if ($destinationId) {
            $query->where('destination_id', $destinationId);
        }

        if ($startDate) {
            $query->where('departure_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('departure_date', '<=', $endDate);
        }


        $travelOrders = $query->get();

        return response()->json($travelOrders, 200);
    }
}
