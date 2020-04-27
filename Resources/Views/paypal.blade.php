@extends('layouts.payment')
@section('title', 'PayPal Confirm Payment')
@section('content')
<header class="header-checkout">
   <div class="container h-100">
      <div class="row justify-content-center">
         <div class="col-lg-5 pl-1 col-11 p-0">
            <h5 class="card-title pt-3">
               @if(request()->has('back'))
               <a href="{{ request()->get('back') }}" title="Back">
               <i class="fas fa-arrow-circle-left"></i> Cancel
               </a> |
               @endif
               @yield('title', config('app.name'))
            </h5>
         </div>
      </div>
   </div>
</header>
<div class="row justify-content-center">
<div class="col-lg-5 col-11">
   <div class="card">
      <div class="card-body">
        <h4 class="card-title">Account Detail </h4>
        <address>
          <strong>Full Name</strong>
          <p>{{ $data->payer->payer_info->first_name ?? '-' }} {{ $data->payer->payer_info->last_name ?? '' }}</p>
          <strong>Email</strong>
          <p>{{ $data->payer->payer_info->email ?? '-' }}</p>
        </address>
        <hr>
        <h4 class="card-title">Shipping Address</h4>
        <address>
          <strong>Recipient Name</strong>
          <p>{{ $data->payer->payer_info->shipping_address->recipient_name ?? '-' }}</p>

          <strong>Address</strong>
          <p>{{ $data->payer->payer_info->shipping_address->line1 ?? '-' }} {{ $data->payer->payer_info->shipping_address->line2 ?? '' }}</p>
          
          <strong>City</strong>
          <p>{{ $data->payer->payer_info->shipping_address->city ?? '-' }}</p>
          <strong>State</strong>
          <p>{{ $data->payer->payer_info->shipping_address->state ?? '-' }}</p>
          <strong>Postal Code</strong>
          <p>{{ $data->payer->payer_info->shipping_address->postal_code ?? '-' }}</p>
        </address>
        <hr>
        <h4 class="card-title">Transactions</h4>
        @php 
          $total = 0;
        @endphp
        @forelse ($data->transactions as $item)
            <div class="card mb-3">
              <div class="card-body">
                <h5 class="card-title">Invoice #{{ $item->invoice_number }}</h5>
                <p>{{ $item->description }}</p>
                @foreach ($item->item_list->items as $list)
                    <table class="table">
                      <tbody>
                        <tr>
                        <td>{{ $list->name }}</td>
                        <td>
                          <strong>{{ $list->price }} {{ $list->currency }}</strong>
                        </td>
                        </tr>
                      </tbody>
                    </table>
                @endforeach
              </div>
            </div>

            @php 
              $total += $item->amount->total;
            @endphp
        @empty
            <strong>No have transactions</strong>
        @endforelse
        
        <form action="" method="POST">
          <button class="btn btn-primary btn-block">Commit</button>
        </form>
        
      </div>
   </div>
</div>
@endsection
@push('scripts')
   {!! JsValidator::formRequest('App\Http\Requests\Member\CheckOutRequest', '#workshop-role-form'); !!}
   <script>
      $('#form-submit').submit(function (e) {
         setTimeout(function() {
            $('.btn-submit').each(function(i, self) {
               var _self = $(self);
               _self.attr('disabled', 'disabled')
               _self.find('.spiner').removeAttr('hidden')
               _self.find('.btn-text').text('Processing...');
            });
         }, 300);
         
      })
   </script>
@endpush
