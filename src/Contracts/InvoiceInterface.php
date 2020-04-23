<?php

  namespace App\Contracts\Models;

  interface InvoiceInterface {

    public function getPartServiceItemsAttribute();

    public function getStatusAfterPaidAttribute();

    public function getInvoiceUrlAttribute();

    public function getCheckoutUrlAttribute();

    public function getRedirectAfterPaidForMemberAttribute();

    public function getRedirectAfterPaidForAdminAttribute();

    public function getDetailTransactionUrlForMemberAttribute();

    public function getDetailTransactionUrlForAdminAttribute();

  }
