<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $guarded = [];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'location_shift_user')->withTimestamps()->withPivot(['location_id', 'shift_id', 'user_id'])->using(LocationShiftUser::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'location_shift_user')->withTimestamps()->withPivot(['location_id', 'shift_id', 'user_id'])->using(LocationShiftUser::class);
    }

}
