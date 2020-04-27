<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\LogException;
use App\Exceptions\ValidateException;

use App\Models\Invoice;
use App\Helpers\SenangPay;
use GuzzleHttp\Client;

use DB;
use Validator;

use App\Repositories\TopUpRepository;

class SenangPayController extends Controller {


    protected $senangpay;
    protected $invoice;


    public function __construct(SenangPay $senangpay) {
        $this->repository = $repository;
        $this->senangpay = $senangpay;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $validator = Validator::make($request->all(), [
            'status_id' => 'required|numeric',
            'order_id' => 'required',
            'msg' => 'required',
            'transaction_id' => 'required',
            'hash' => 'required'
        ]);


        try {

            if($validator->fails()) {
                throw new \Exception('Request parameters is fail');
            }

            $this->topup = $this->repository->findTopup($request->order_id);

            if($this->topup->due_date < now()) {
                throw new \Exception("Payment has been expired");

            }

            if(is_null($this->topup)) {
                throw new \Exception("Invoice cannot be found");

            }

            if($this->senangpay->validating_hash($request) != $request->hash) {
                throw new \Exception('Parameter hash invalid to verification!');
            }

            DB::beginTransaction();
            $client = new Client();
            $res = $client->request('GET', $this->senangpay->api_url('query_order_status'), [
                'query' => [
                'merchant_id' => $this->senangpay->getMarchantid(),
                'order_id' => $request->order_id,
                'hash' => md5($this->senangpay->getMarchantid() . $this->senangpay->getSecret() . $request->order_id)
                ]
            ]);
            $json = json_decode($res->getBody());

            if($json->status != 1) {
                throw new \Exception('Senangpay problem! ' . $json->msg);
            }
            if($json->status == 1 && $json->data) {
                if(isset($json->data[0]) && $json->data[0]->payment_info->status == 'paid'){
                    $payment_info = $json->data[0]->payment_info;
                    $this->topup->update([
                    'state' => 'paid',
                    'payment_method' => 'senangpay',
                    'reference_number' => $json->data[0]->payment_info->transaction_reference,
                    'payload' => $json,
                    ]);
                    DB::commit();
                    flash()->success('Top up successfully paid!');
                    return redirect()->route('member.topup.show', $this->topup->uuid);
                }
            } else {
                flash()->danger("Top Up #{$this->topup->transaction_number} is unpaid");
            }

            DB::rollBack();
            throw new ValidateException('Senangpay problem! ' . $json->msg);
        }  catch (ValidateException $e) {
            DB::rollBack();
            flash()->danger($e->getMessage());
            return redirect()->route('member.checkout.show', $this->topup->uuid);
        } catch (LogException $e) {
            DB::rollBack();
            flash()->danger($e->getMessage());
            return redirect()->route('member.checkout.show', $this->topup->uuid);
        }
    }

    public function cek_invoice($topup) {
        try {

            DB::beginTransaction();
            $client = new Client();

            $res = $client->request('GET', $this->senangpay->api_url('query_order_status'), [
                'query' => [
                    'merchant_id' => $this->senangpay->getMarchantid(),
                    'order_id' => $topup->id,
                    'hash' => md5($this->senangpay->getMarchantid() . $this->senangpay->getSecret() . $topup->id)
                ]
            ]);


            $json = json_decode($res->getBody());
            // dd($json);
            if($json->status != 1) {
                flash()->danger('Senangpay problem! ' . $json->msg);
            }

            if($json->data){
                $payment_info = $json->data[0]->payment_info;
                if($json->status == 1 && $payment_info == 'paid') {
                    $topup->update([
                    'state' => 'paid',
                    'payment_method' => 'senangpay',
                    'reference_number' => $payment_info->transaction_reference,
                    'payload' => $json
                    ]);
                    DB::commit();
                    flash()->success("Invoice {$topup->transaction_number} has been updated");
                }
            } else {
                flash()->danger("Top Up #{$topup->transaction_number} is unpaid");
            }

            DB::rollBack();
            // flash()->danger('Senangpay problem! ' . $json->msg);
        } catch (LogException $e) {
            DB::rollBack();
            flash()->danger($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
