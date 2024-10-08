<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employees()
    {
        return $this->belongsToMany(Employee::class)->using(EmployeeSite::class);
    }

    public function principal_employees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
