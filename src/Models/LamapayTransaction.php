<?php

namespace Nhinstein\Lamapay\Models;

use Illuminate\Database\Eloquent\Model;

class LamapayTransaction extends Model {

    protected $fillable = [
        'transaction_number',
        'reference_number',
        'amount',
        'model_class_type',
        'model_class_id',
        'state',
        'type'
    ];
}
