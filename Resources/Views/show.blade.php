@extends('layouts.payment')
@section('title', 'Payment')
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
         <div class="row form-checkout justify-content-center">
            <div class="col-lg-12 col-12">
               {{ Form::open(['route' => ['lamapay.checkout.update', $data->uuid], 'id' => 'form-submit', 'method' => 'PUT']) }}
               <div class="row justify-content-md-center">
                  <div class="col-lg-12">
                     <div class="mb-2 mt-2">
                        <h6 class="mb-0">Customer Details</h6>
                     </div>
                     <div class="row">
                        <div class="col">
                           @component(component_member_path('form_group'), [ 'label_name' => 'Full Name', 'label_id' => 'name', 'messages' => $errors->get('name'), 'required' => true ])
                           {{ Form::text('name', old('name', $user->name), ['class' => 'form-control', 'id' => 'name', 'required' => 'required']) }}
                           @endcomponent
                        </div>
                     </div>
                     <div class="row">
                        <div class="col">
                           @component(component_member_path('form_group'), [ 'label_name' => 'Email', 'label_id' => 'email', 'messages' => $errors->get('email'), 'required' => true ])
                           {{ Form::email('email', old('email', $user->email), ['class' => 'form-control', 'id' => 'email', 'required' => 'required']) }}
                           @endcomponent
                        </div>
                     </div>
                     <div class="row">
                        <div class="col">
                           @component(component_member_path('form_group'), [ 'label_name' => 'Phone Number', 'label_id' => 'phone', 'messages' => $errors->get('phone'), 'required' => true ])
                           {{ Form::text('phone', old('phone', $user->phone_number), ['class' => 'form-control', 'id' => 'phone', 'required' => 'required']) }}
                           @endcomponent

                           <input type="hidden" name="cancel_url" value="{{ request()->fullurl() }}">
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-lg-12 col-xl-12">
               <div class="mb-1 mt-2">
                  <h6 class="mb-0">Choose Payment Method</h6>
               </div>
               <div id="accordion3" class="card-accordion">
                  <div class="card collapse-icon accordion-icon-rotate mb-3">
                     <div class="card">
                        <div class="card-header collapsed" id="headingGOne">
                           <h5 class="mb-0">
                              <button type="button" class="btn btn-link btn-block text-left collapsed" data-toggle="collapse" data-target="#accordionC1" aria-expanded="false" aria-controls="accordionC1">
                                 Credit Card
                              </button>
                           </h5>
                        </div>
                        <div id="accordionC1" class="collapse show" aria-labelledby="headingGOne" data-parent="#accordion3" style="">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-12">
                                    <img class="img-fluid" src="{{ asset('/images/creditcard.png') }}">

                                    <button type="submit" name="payment_method" value="senangpay" class="btn btn-submit btn-block mt-2 btn-danger">
                                       <span class="spinner-border spinner-border-sm spiner" hidden role="status" aria-hidden="true"></span>
                                       <span class="btn-text">Pay Now</span>
                                    </button>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               
                  @if(env('PAYPAL_ENABLED', false))
                  <div class="card collapse-icon accordion-icon-rotate">
                     <div class="card">
                        <div class="card-header collapsed" id="headingGOne">
                           <h5 class="mb-0">
                              <button type="button" class="btn btn-link btn-block text-left collapsed" data-toggle="collapse" data-target="#accordionC2" aria-expanded="false" aria-controls="accordionC1">
                                 PayPal
                              </button>
                           </h5>
                        </div>
                        <div id="accordionC2" class="collapse" aria-labelledby="headingGOne" data-parent="#accordion3" style="">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col-12">
                                    <img class="img-fluid" src="{{ asset('/images/paypal.png') }}">

                                    <button type="submit" name="payment_method" value="paypal" class="btn btn-block btn-submit mt-2 btn-danger">
                                       <span class="spinner-border spinner-border-sm spiner" hidden role="status" aria-hidden="true"></span>
                                       <span class="btn-text">Pay Now</span>
                                    </button>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  @endif


               </div>
            </div>
         </div>
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
