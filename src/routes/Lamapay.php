<?php 

namespace Nhinstein\Lamapay\Routes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route as BaseRoute;
use Nhintein\Lamapay\Http\Middleware\LamapayAuthenticate;

class Lamapay {

  public static  function route($function) {
    // BaseRoute::group([ 
    //   'prefix' => 'lamapay',
    //   'as' => 'lamapay.'
    // ], function() use ($function) {
    //     BaseRoute::resource('/topup', 'TopUpController');
    //     BaseRoute::resource('/checkout', 'CheckoutController')->only(['show', 'store', 'update']);
    //     BaseRoute::resource('/invoice', 'InvoiceController')->only(['index']);
    // });

    BaseRoute::resource('/senangpay/callback', 'Web\SenangPayController')->only(['index']);
    BaseRoute::resource('/paypal/callback', 'Web\PayPalController')->only(['index']);
    BaseRoute::resource('/checkout', 'CheckoutController')->only(['show', 'store', 'update'])->as('lamapay.checkout');
    // BaseRoute::group([ 
    //   'prefix' => 'auth',
    //   'namespace' => 'Member',
    //   'as' => 'auth.'
    // ], function() use ($function) {
    //   Auth::routes([ 'register' => false ]);
    //   BaseRoute::group([
    //     'middleware' => [ LamapayAuthenticate::class ],
    //   ], function() use ($function) {


    //     // BaseRoute::resource('/topup', 'TopUpController');
    //     // BaseRoute::resource('/checkout', 'CheckoutController')->only(['show', 'store', 'update']);
    //     // BaseRoute::resource('/invoice', 'InvoiceController')->only(['index']);

    //     $function();
  
    //   });
    // });
  }

}