<?php  

namespace Nhinstein\Lamapay\Helpers;
use  Nhinstein\Lamapay\Models\LamapayTransaction;
use  Nhinstein\Lamapay\Models\LamapayInvoice;

class Lamapay {

  protected $user;

  public function __construct() {
    $this->user = config('lamapay.transaction.user');
    $this->currency_iso = config('lamapay.currency_iso');
    $this->currency_code = config('lamapay.currency_code');
  }
  
  public static function create_transaction($transaction, $field_amount) {

    LamapayTransaction::create([        
          'transaction_number' => rand(),
          'reference_number' => rand(),
          'amount' => $field_amount,
          'model_class_type' => get_class($transaction),
          'model_class_id' => $transaction->id,
          'state' => 'submitted'
    ]);

  }
  
  public static function create_invoice($transaction, $request, $method) {

    LamapayInvoice::create([        
      'user_id' => $this->user->id,
      'model_class_type' => get_class($lamapaytransaction),
      'model_class_id' => $lamapaytransaction->id,
      'description',
      'transaction_number' => $lamapaytransaction->transaction_number,
      'name' => $request->name,
      'email' => $request->email,
      'phone' => $request->phone,
      'sub_total' => $lamapaytransaction->amount,
      'total' => $lamapaytransaction->amount,
      'currency_iso' => $this->currency_iso,
      'currency_code' => $this->currency_code,
    ]);

  }

}