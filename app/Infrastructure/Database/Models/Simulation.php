<?php

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property int $id
 * @property int $modality_id
 * @property double $total_amount
 * @property double $amount_requested
 * @property double $monthly_interest_rate
 * @property double $quantity_installment
 */
class Simulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_amount',
        'amount_requested',
        'monthly_interest_rate',
        'quantity_installment',
        'modality_id'
    ];

    /**
     * @return BelongsTo
     */
    public function modality(): BelongsTo
    {
        return $this->belongsTo(Modality::class);
    }
}
