<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $guarded = [];

    public function writer()
    {
        return $this->belongsTo(User::class, 'writer_id')->where('is_writer', true);
    }

    public function typology()
    {
        return $this->belongsTo(Typology::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class)->using(EmployeeSite::class);
    }

    public function timeTracking(): HasMany
    {
        return $this->hasMany(TimeTracking::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    public function employeeSite(): HasMany
    {
        return $this->hasMany(EmployeeSite::class);
    }

    public function principal_site()
    {
        return $this->belongsTo(Site::class, 'principal_site_id', 'id');
    }

    public function checkIn($checkIn): void
    {
        $this->timeTracking()->create([
            'employee_id' => $this->id,
            'check_in' => date('Y-m-d') . ' ' . $checkIn
        ]);
    }

    public function checkOut($checkOut): void
    {
        $timeTracking =  $this->timeTracking()->where([
            'employee_id' => $this->id,
            'check_in' => $this->timeTracking->last()->check_in,
        ])->firstOrFail();
        $timeTracking->check_out = date('Y-m-d') . ' ' . $checkOut;
        $timeTracking->save();
    }

    public function newReceipt($data): void
    {
//        dd($this, $data);
        $this->receipts()->create([
            'employee_id' => $this->id,
            'date' => $data['date'],
            'sites' => $data['sites']
        ]);
    }

    public function isOnline(): bool
    {
        if(!$this?->timeTracking()?->exists() || $this?->timeTracking?->last()?->check_out) {
            return false;
        }
        return true;

//        return $this->timeTracking()
//            ->whereDate('check_in', today())
//            ->where(function ($query) {
//                $query->whereDate('check_out', '!=', today())
//                    ->orWhereNull('check_out');
//            })
//            ->exists();
    }

    public function finishedWorkedToday(): bool
    {
        return $this->timeTracking()
            ->whereDate('check_in', today())
            ->whereDate('check_out', today())
            ->exists();
    }
}
