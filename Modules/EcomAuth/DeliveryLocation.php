<?php

namespace Modules\EcomAuth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\BusinessLocation;

class DeliveryLocation extends Model
{
    use HasFactory;

    protected $table = 'ecom_delivery_locations'; // Ensure it matches your database table name

    protected $fillable = ['business_location_id', 'pincode'];

    /**
     * Relationship with BusinessLocation.
     */
    public function businessLocation()
    {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }
}