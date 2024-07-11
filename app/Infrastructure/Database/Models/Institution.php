<?php

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property int $id
 * @property string $name
 */
class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];


    /**
     * @return HasMany
     */
    public function modalities(): HasMany
    {
        return $this->hasMany(Modality::class);
    }
}
