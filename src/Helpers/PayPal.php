<?php  

namespace Nhinstein\Lamapay\Helpers;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;


class PayPal {

  protected $marchantid;
  protected $secret;
  protected $sandbox;

  public function __construct() {
    $this->client = config('lamapay.paypal.client');
    $this->secret = config('lamapay.paypal.secret');
    $this->sandbox = config('lamapay.paypal.sandbox');
  }
  
  public static function apiContext() {
    $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential($this->client, $this->secret)      // ClientSecret
    );

    $apiContext->setConfig([
      $this->sanbox ? 'sandbox' : 'live'
    ]);

    return $apiContext;
  }

  public function payment($payload) {
    $payer = new Payer();
    $payer->setPaymentMethod("paypal");

    $items = [];
    $subtotal = 0;
    $total = 0;
    foreach($payload['items'] as $i => $item) {
      $items[$i] = (new Item())
                    ->setName($item['name'])
                    ->setCurrency('USD')
                    ->setQuantity($item['qty'])
                    ->setPrice($item['price']);

      $subtotal += $item['price'];
      $total += $item['price'] * $item['qty'];
    }
    
    $itemList = new ItemList();
    $itemList->setItems($items);

    $details = new Details();
    $details->setShipping(0)
        ->setSubtotal($subtotal);

    $amount = new Amount();
    $amount->setCurrency("USD")
        ->setTotal($total)
        ->setDetails($details);

    $transaction = new Transaction();
    $transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription($payload['description'])
        ->setInvoiceNumber($payload['invoice_number']);
    
    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl(url('/paypal/callback'))
        ->setCancelUrl($payload['cancel_url']);

    $payment = new Payment();
    $payment->setIntent("sale")
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrls)
        ->setTransactions(array($transaction));

    $request = clone $payment;

    try {
      $payment->create($this->apiContext());
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
    $approvalUrl = $payment->getApprovalLink();
    return $approvalUrl;
  }

  public function info($request) {
    $paymentId = $request['paymentId'];
    return Payment::get($paymentId, $this->apiContext());
  }

  public function excecute($request) {

    $paymentId = $request['paymentId'];
    $payment = Payment::get($paymentId, $this->apiContext());
    
    $execution = new PaymentExecution();
    $execution->setPayerId($request['PayerID']);

    $transaction = new Transaction();
    $amount = new Amount();
    $details = new Details();

    $details->setShipping(0)
        ->setSubtotal($payment->transactions[0]->amount->details->subtotal);

    $amount->setCurrency($payment->transactions[0]->amount->currency);
    $amount->setTotal($payment->transactions[0]->amount->total);
    $amount->setDetails($details);
    $transaction->setAmount($amount);

    $execution->addTransaction($transaction);

    try {
      
      $result = $payment->execute($execution, $this->apiContext());

      try {
        return Payment::get($paymentId, $this->apiContext());

      } catch (\Exception $e) {
        throw new \Exception($e->getMessage());
      }
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }

  }

}