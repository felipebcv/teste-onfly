<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="TravelOrder",
 *     type="object",
 *     title="Travel Order",
 *     required={"id", "user_id", "destination_id", "departure_date", "return_date", "status_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1, description="ID of the client"),
 *     @OA\Property(property="destination_id", type="integer", example=3, description="ID of the destination"),
 *     @OA\Property(property="departure_date", type="string", format="date", example="2025-05-01"),
 *     @OA\Property(property="return_date", type="string", format="date", example="2025-05-10"),
 *     @OA\Property(property="status_id", type="integer", example=1, description="Status ID (1 for requested)"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-05T15:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-05T15:00:00Z")
 * )
 */

class TravelOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'destination_id',
        'travel_date',
        'departure_date',
        'return_date',
        'status_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function status()
    {
        return $this->belongsTo(TravelOrderStatus::class);
    }

}
