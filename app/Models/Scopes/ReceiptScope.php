<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ReceiptScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if(auth()->user()->hasRole('Manager') && auth()->user()?->shifts) {
            $builder->whereRelation('employee.location', function ($location) use ($model) {
                return $location->whereIn('shift_id', auth()->user()->shifts->pluck('id')->toArray())
                    ->whereRelation('users', function ($users) {
                        return $users->where('users.id', auth()->user()->id);
                    })
                    ;
            });
        } else if(auth()->user()->is_writer) {
            $builder->where('writer_id', auth()->user()->id);
        }
    }
}
