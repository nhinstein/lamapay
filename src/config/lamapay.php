<?php 

    return [
    
        'senangpay' => [
            'marchantid' => env('SENANGPAY_MARCHANT_ID', null),
            'secret' => env('SENANGPAY_SECRET_KEY', null),
            'sandbox' => env('SENANGPAY_SANDBOX', false)
        ],
    
        'paypal' => [
            'client' => env('PAYPAL_CLIENT', null),
            'secret' => env('PAYPAL_SECRET_KEY', null),
            'sandbox' => env('PAYPAL_SANDBOX', false)
        ],
    
        /**
         * Authentication Setting
         */
        'auth' => [
          'user' => App\User::class,
          'guard' => 'auth'
        ],
    
        /**
         * Notification status
         */
        'notification' => true

    ];