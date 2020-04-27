<?php

namespace Nhinstein\Lamapay\Models;

class LamapayInvoice extends Model {

    protected $fillable = [
        'user_id',
        'model_class_id',
        'model_class_type',
        'description',
        'transaction_number',
        'duedate',
        'payment_approve_at',
        'payload',
        'payment_method_transaction_id',
        'payment_method',
        'name',
        'email',
        'phone',
        'sub_total',
        'fee',
        'total',
        'currency_iso',
        'currency_code',
    ];

        protected $dates = ['due_date', 'payment_approve_at'];

        protected $casts = [
            'payload' => 'collection'
        ];
}
