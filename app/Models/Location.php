<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $guarded = [];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'location_shift_user')->withTimestamps()->using(LocationShiftUser::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'location_shift_user')->withTimestamps()->using(LocationShiftUser::class);
    }

    // Filament uses this to fill the Repeater
    public function customerFields(): HasMany
    {
        return $this->hasMany(LocationShiftUser::class);
    }

}
