<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\PayPal;
use App\Repositories\TopUpRepository;

class PayPalController extends Controller {

    protected $repository;
    protected $topup;

    public function __construct(TopUpRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $paypal = (new PayPal)->excecute($request->all());
        if(in_array($paypal->state, ['approved'])) {
            $invoice_number = $paypal->transactions[0]->invoice_number ?? 0;
            $this->topup = $this->repository->findTopup($invoice_number);

            try {

                if(is_null($this->topup)) {
                    throw new \Exception("Invoice cannot be found");
                }

                $this->topup->update([
                    'state' => 'paid',
                    'payment_method' => 'paypal',
                    'reference_number' => $paypal->id,
                    'payload' => $paypal->toJson(),
                ]);
    
                flash()->success('Top up successfully paid!');
                return redirect()->route('member.topup.show', $this->topup->uuid);

            } catch (\Exception $e) {
                flash()->danger($e->getMessage());
                return redirect()->route('member.topup.show', $this->topup->uuid);
            }
            



        }
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
        $paypal = (new PayPal)->info($request->all());
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
