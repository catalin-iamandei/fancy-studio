<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $guarded = [];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'location_shift_user')->using(LocationShiftUser::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'location_shift_user')->using(LocationShiftUser::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'location_shift_user')->using(LocationShiftUser::class);
    }
}
