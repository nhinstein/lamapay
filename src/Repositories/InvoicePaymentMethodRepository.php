<?php

namespace App\Repositories;
use DB;

use App\Models\InvoicePaymentMethod;
use App\Models\Invoice;
use App\Http\Resources\Member\Sales\InvoiceResource;

class InvoicePaymentMethodRepository extends Repository {
    protected $modelInv;

    public function __construct(InvoicePaymentMethod $model, Invoice $modelInv) {
        parent::__construct($model);
        $this->modelInv = $modelInv;
    }

    public function store($request, $invoice)
    {
        DB::beginTransaction();
        try {
            foreach ($request->payments as $payment) {
                if(isset($payment['uuid'])){
                    $paymentmethod = $this->findByUuid($payment['uuid']);
                    $paymentmethod->update([
                        'amount' => $payment['amount'],
                        'card_number' => $payment['card_number'],
                    ]);
                }
            }
            if($request->has('payment')){
                $payment = $request->get('payment');
                $invoice->payment_methods()->create([
                    'name' => $payment['payment_name'],
                    'card_number' => isset($payment['card_number']) ? $payment['card_number'] : null,
                    'amount' => $payment['amount'],
                    'state' => 'paid'
                ]);
            }
            if($invoice->is_paid == true){
                $inv_state = 'paid';
            } else {
                $inv_state = 'unpaid';
            }
            $invoice->update(['state' => $inv_state, 'total_payment_receive' => $invoice->payment_received]);
            DB::commit();
            return new InvoiceResource($invoice);
        } catch (\Exception $e) {
            flash()->warning($e->getMessage());
            DB::rollback();
            return response()->json($e->getMessage(), 500);
        }
    }

    public function add_payment($request, $invoice)
    {
        DB::beginTransaction();
        try {
            $invoice->payment_methods()->create([
                'name' => $request->payment_name,
                'card_number' => $request->card_number,
                'amount' => $request->amount,
                'state' => 'paid'
            ]);
            if($invoice->is_paid == true){
                $inv_state = 'paid';
            } else {
                $inv_state = 'unpaid';
            }
            $invoice->update(['state' => $inv_state, 'total_payment_receive' => $invoice->payment_received]);
            DB::commit();
            return new InvoiceResource($invoice);
        } catch (\Exception $e) {
            flash()->warning($e->getMessage());
            DB::rollback();
            return response()->json($e->getMessage(), 500);
        }
    }

    public function delete_payment($request, $uuid)
    {
        DB::beginTransaction();
        try {
            $this->findByuuid($uuid)->delete();
            $invoice = $this->modelInv->getByUuid($request->invoice_uuid);
            if($invoice->is_paid == true){
                $inv_state = 'paid';
            } else {
                $inv_state = 'unpaid';
            }
            $invoice->update(['state' => $inv_state, 'total_payment_receive' => $invoice->payment_received]);
            DB::commit();
            return new InvoiceResource($invoice);
        } catch (\Exception $e) {
            flash()->warning($e->getMessage());
            DB::rollback();
            return response()->json($e->getMessage(), 500);
        }
    }

}
