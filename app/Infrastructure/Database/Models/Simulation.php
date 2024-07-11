<?php

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property int $id
 * @property int $modality_id
 * @property double $totalAmount
 * @property double $amountRequested
 * @property double $monthlyInterestRate
 * @property double $quantityInstallment
 */
class Simulation extends Model
{
    use HasFactory;


    /**
     * @return HasMany
     */
    public function modalities(): HasMany
    {
        return $this->hasMany(Modality::class);
    }
}
