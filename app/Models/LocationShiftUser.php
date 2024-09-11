<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LocationShiftUser extends Pivot
{
    protected $table = 'location_shift_user';
    protected $guarded = [];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
