<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'amount' => 'float'
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Receipt $receipt) {
            $receipt->writer_id = auth()->user()->id;
        });
    }

    public function writer()
    {
        return $this->belongsTo(User::class, 'writer_id');
    }
    public function site(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
