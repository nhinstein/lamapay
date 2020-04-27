<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\TopUpRepository;
use Illuminate\Validation\Rule;
use App\Helpers\PayPal;

class CheckoutController extends Controller
{
    protected $repository;

    public function __construct(TopUpRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function show($id) {
        $data['data'] = $this->repository->findByUuid($id);
        $data['user'] = auth()->user();
        return view('member.checkout.show', $data);

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
        $data = $request->validate([
            'payment_method' => ['required', Rule::in(['senangpay', 'paypal'])],
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:8'
        ]);
        $topup = $this->repository->findByUuid($id);
        auth()->user()->update(['phone_number' => $request->phone]);
        // return $topup;
        switch($request->payment_method) {
            case"senangpay":
                $data['data'] = $topup;
                $data['user'] = $request;
                $data['detail'] = 'Payment for invoice number #' . $topup->transaction_number;
                return view('member.checkout.submit_senangpay', $data);
                break;
            case"paypal":
                $payload = [
                    'invoice_number' => $topup->transaction_number,
                    'description' => 'Top Up Balance',
                    'items' => [
                        [
                            'name' => 'Payment for invoice number #' . $topup->transaction_number,
                            'qty' => 1,
                            'price' => round(convert_amount($topup->amount, 'USD'), 2)
                        ]
                    ],
                     'cancel_url' => $request->cancel_url
                ];
                
                try {
                    $paynow = (new PayPal)->payment($payload);
                    return redirect()->to($paynow);
                } catch (\Exception $e) {
                    flash()->danger($e->getMessage());
                    return redirect()->back();
                }
            break;
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
