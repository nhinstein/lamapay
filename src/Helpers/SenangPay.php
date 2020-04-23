<?php 

namespace App\Helpers;

class SenangPay {

  protected $marchantid;
  protected $secret;
  protected $sandbox;

  public function __construct() {
    $this->marchantid = config('lamapay.senangpay.marchantid');
    $this->secret = config('lamapay.senangpay.secret');
    $this->sandbox = config('lamapay.senangpay.sandbox');
  }

  public function getMarchantid() {
    return $this->marchantid;
  }
  public function getSecret() {
    return $this->secret;
  }

  public function baseurl() {
    return $this->sandbox ? 'https://sandbox.senangpay.my' : 'https://app.senangpay.my';
  }

  public function payment_url() {
    return $this->baseurl() . '/payment/' . $this->marchantid;
  }

  public function api_url($endpoin) {
    return $this->sandbox ? $this->baseurl() . '/apiv1/' . $endpoin: $this->baseurl() . '/apiv1/' . $endpoin;
  }

  public function hasing ($detail, $amount, $order_id) {
    return md5($this->secret . urldecode($detail) . urldecode($amount) . urldecode($order_id));
  }

  public function validating_hash($request) {
    return md5($this->secret . $request->status_id . $request->order_id . $request->transaction_id . $request->msg);
  }

}
