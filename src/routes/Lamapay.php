<?php 

namespace Hexters\Ladmin\Routes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route as BaseRoute;
use Nhintein\Lamapay\Http\Middleware\LadminLoginMiddleware;

class Ladmin {

  public static  function route($function) {

    BaseRoute::resource('/senangpay/callback', 'SenangPayController')->only(['index']);
    BaseRoute::resource('/paypal/callback', 'PayPalController')->only(['index']);
    BaseRoute::group([ 
      'prefix' => 'member',
      'namespace' => 'Member',
      'as' => 'member.'
    ], function() use ($function) {
      Auth::routes([ 'register' => false ]);
      BaseRoute::group([
        'middleware' => [ LamapayLoginMiddleware::class ],
      ], function() use ($function) {


        BaseRoute::resource('/topup', 'TopUpController');
        BaseRoute::resource('/checkout', 'CheckoutController')->only(['show', 'store', 'update']);
        BaseRoute::resource('/invoice', 'InvoiceController')->only(['index']);

        $function();
  
      });
    });
  }

}