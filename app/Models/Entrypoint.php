<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class Entrypoint extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name'
    ];

    /**
     * Get all of the slots for the entrypoint.
     */
    public function slots(): BelongsToMany
    {
        return $this->belongsToMany(Slot::class)
            ->orderByPivot('distance')
            ->withPivot('distance');
    }
}
