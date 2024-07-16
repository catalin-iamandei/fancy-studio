<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function typology()
    {
        return $this->belongsTo(Typology::class);
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class);
    }

    public function principal_site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
