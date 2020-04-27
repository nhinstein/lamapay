<?php 

namespace App\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;

use App\Models\Invoice;

class InvoiceRepository extends Repository implements BaseRepositoryInterface {

    protected $model;

    public function __construct(Invoice $model) {
        $this->model = $model;
    }

    public function original() {
        return $this->model;
    }

    public function paginate($limit) {
        return member()->invoices()->search()->orderby('updated_at', 'DESC')->paginate($limit);
    }

    public function findByTransactionNumber($transaction_number) {
        return $this->model->where('transaction_number', $transaction_number)->first();

    }

    public function findUnpaidInvoice($id) {
        return $this->model->where('state', 'unpaid')->whereId($id)->firstOrFail();
    }

}
