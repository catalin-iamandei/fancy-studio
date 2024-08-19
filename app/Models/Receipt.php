<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
            'amount' => 'float',
            'sites' => 'array'
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Receipt $receipt) {
            $receipt->writer_id = auth()->user()->id;
            $receipt->amount = $receipt->getTotalAmount();
        });

        static::updating(function (Receipt $receipt) {
            $receipt->amount = $receipt->getTotalAmount();
        });
    }

    public function writer()
    {
        return $this->belongsTo(User::class, 'writer_id');
    }
//    public function site(): \Illuminate\Database\Eloquent\Relations\BelongsTo
//    {
//        return $this->belongsTo(Site::class);
//    }
    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getTotalAmount()
    {
        $amounts = array_column($this->sites, 'amount');

        if(!$this->sites || !$amounts) {
            return 0;
        }

        return array_sum($amounts);
    }
}
