<?php 

    return [
    
        'senangpay' => [
            'marchantid' => env('SENANGPAY_MARCHANT_ID', null),
            'secret' => env('SENANGPAY_SECRET_KEY', null),
            'sandbox' => env('SENANGPAY_SANDBOX', false)
        ],
    
        /**
         * Authentication Setting
         */
        'auth' => [
          'user' => App\User::class,
          'guard' => 'member'
        ],
    
        /**
         * Notification status
         */
        'notification' => true

    ];