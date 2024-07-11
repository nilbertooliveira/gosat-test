<?php

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property int $id
 * @property int $institution_id
 * @property string $name
 * @property string $code
 */
class Modality extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'institution_id'
    ];

    /**
     * @return BelongsTo
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * @return HasMany
     */
    public function simulations(): HasMany
    {
        return $this->hasMany(Simulation::class);
    }
}
