<?php

namespace App\Repositories;

use Nhinstein\Lamapay\Models\LamapayInvoice;
use App\Exceptions\LogException;
use App\Exceptions\ValidateException;
// use App\Http\Resources\Member\Sales\InvoiceResourceCollection;

class InvoiceRepository extends Repository {

    public function __construct(Invoice $model) {
        parent::__construct($model);
    }

    public function datatables()
    {
        // $query  = request()->workshop->invoices();

        // $states = request()->get('state', null);

        // if ($states) $query->whereState($states);

        // return DataTables::of($query)
        //     ->addColumn('type', function ($item){
        //         return "<a href=". $item->detail->member_detail_url . " target='_blank'>" . $item->detail->type_invoice . "</a>";
        //     })
        //     ->addColumn('reg_no', function ($item){
        //         return $item->detail->vehicle ? $item->detail->vehicle->vehicle_reg_number : '-';
        //     })
        //     ->editColumn('state', function ($item){
        //         return ($item->state == 'paid') ? "<small class='badge badge-success'>Closed</small>" : "<small class='badge badge-danger'>".ucwords($item->state)."</small>";
        //     })
        //     ->editColumn('created_at', function($item){
        //         return $item->created_at->format('d/m/y');
        //     })
        //     ->addColumn('action', function($item){
        //         return view('components.datatable.button', ['item' => $item]);
        //     })
        //     ->escapeColumns([])
        //     ->make(true);
    }



    public function updated($request, $uuid) {
        // try {
        //     DB::begintransaction();
        //     $invoice = $this->findByUuid($uuid);
        //     $invoice->update(['remarks' => $request->remarks]);
        //     flash()->success("Invoice successfully updated!");
        //     DB::commit();
        //     return redirect()->route('member.cashierinvoice.edit', [$invoice->uuid, 'back' => route('member.cashierinvoice.index')]);
        // } catch (ValidateException $e) {
        //     DB::rollBack();
        //     flash()->danger($e->getMessage());
        //     return redirect()->back()->withInput();
        // } catch (LogException $e) {
        //     DB::rollBack();
        //     flash()->danger($e->getMessage());
        //     return redirect()->back()->withInput();
        // }

    }

    public function get_invoices($request)
    {
        // $invoices = $request->workshop->invoices()->whereState('unpaid');
        // $invoices = $request->workshop->invoices();
        // if(request()->has('q')){
        //     $invoices = $invoices->where('invoice_number', 'LIKE', '%'. request()->get('q') . '%')
        //                 ->orWhere('vehicle_reg_number', 'LIKE', '%'. request()->get('q') . '%');
        //     $invoices = $invoices->take(5)->get();
        //     return new InvoiceResourceCollection($invoices);
        // }
        // return [];
    }

}
