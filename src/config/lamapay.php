<?php 

    return [


        'models' => [
            'roles' => App\Role::class
        ],

        /*
         | --------------------------------------
         | Assign configuration menu
         | --------------------------------------
         */
        'prefix_link' => 'admin',
        
        'auth_middleware' => 'auth',

        'template' => [
            'extends' => 'layouts.app',
            'content' => 'content'
        ],
    
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
        
        'transaction' => [
            'user' => App\User::class,
            'guard' => 'auth'
        ],
        'currency_code' => 'IDR',
        'currency_iso' => 'RP'

    ];