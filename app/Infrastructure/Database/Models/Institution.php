<?php

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property int $id
 * @property string $name
 * @property string $code
 */
class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code'
    ];


    /**
     * @return HasMany
     */
    public function modalities(): HasMany
    {
        return $this->hasMany(Modality::class);
    }
}
