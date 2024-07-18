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

    public function timeTracking()
    {
        return $this->belongsTo(TimeTracking::class);
    }

    public function employeeSite(): HasMany
    {
        return $this->hasMany(EmployeeSite::class);
    }

    public function principal_site()
    {
        return $this->belongsTo(Site::class, 'principal_site_id', 'id');
    }
}
