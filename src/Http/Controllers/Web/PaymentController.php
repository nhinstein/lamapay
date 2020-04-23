<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\MerchantRepository;
use App\Http\Requests\Web\Payments\UpdatePaymentRequest;
use Validator;

class PaymentController extends Controller {

    protected $repository;

    public function __construct(MerchantRepository $repository) {
        $this->repository = $repository;
    } 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id, $token) {
        $merchant = $this->repository->findByMerchantId($id);
        $ready = $merchant->api_planing_transactions()->where('token', $token)->where('secret', $merchant->api_merchant_secret)->first();
        if(!$ready) {
            return abort(404);
        }

        if($ready->expired_at < now()) {
            return abort(419, 'Payment Expired');
        }
        $payload = $ready->payload;
        $payload['token'] = $token;
        $data['merchant'] = $merchant;
        $data['payload'] = $payload;
        return view('web.payment.show', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'hash' => 'required',
            'callback_url' => 'required|url',
            'product_name' => 'required',
            'product_price' => 'required|numeric',
            'product_qty' => 'required|numeric',
            'order_id' => 'required',
            'note' => 'nullable|max:120',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        if($request->ajax()) {
            return response()->json($this->repository->payment_config($id));
        }
        $merchant = $this->repository->findByMerchantId($id);
        if(!$merchant->is_active) abort(404);

        $data['merchant'] = $merchant;
        return view('web.payment.show', $data);
        
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
    public function update(Request $request, $id) {
        
        $valid = Validator::make($request->all(), [
            'merchant_id' => 'required',
            'salary_slip' => 'required|image',
            'ic_slip' => 'required|image',
            'data' => 'required'
        ]);

        if($valid->fails()) {
            return $valid->messages();
        }

        try {
            $transaction = $this->repository->create_transaction($request);
            return response()->json([
                'result' => true,
                'id' => $transaction->id,
                'trx_number' => $transaction->transaction_number
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => $e->getMessage()
            ]);
        }
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
