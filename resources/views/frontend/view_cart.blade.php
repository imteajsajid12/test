{{-- @extends('frontend.layouts.app')

@section('content')
    <!-- Steps -->
    <section class="pt-5 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row gutters-5 sm-gutters-10">
                        <div class="col active">
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                                <i class="la-3x mb-2 las la-shopping-cart cart-animate" style="margin-left: -100px; transition: 2s;"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('1. My Cart') }}</h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-map"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('2. Shipping info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-truck"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('3. Delivery info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('4. Payment') }}</h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('5. Confirmation') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cart Details -->
    <section class="mb-4" id="cart-summary">
        @include('frontend.'.get_setting('homepage_select').'.partials.cart_details', ['carts' => $carts])
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        function removeFromCartView(e, key) {
            e.preventDefault();
            removeFromCart(key);
        }

        function updateQuantity(key, element) {
            $.post('{{ route('cart.updateQuantity') }}', {
                _token: AIZ.data.csrf,
                id: key,
                quantity: element.value
            }, function(data) {
                updateNavCart(data.nav_cart_view, data.cart_count);
                $('#cart-summary').html(data.cart_view);
            });
        }
    </script>
@endsection --}}


@extends('frontend.layouts.app')

@section('content')
<style>
.section_main {
    box-shadow: 0 0px 27px rgb(40 42 53 / 20%);
    padding: 15px;
    border-radius: 5px;
}
.checkout{
          margin: 30px 0;
  }
  @media (min-width: 1200px){
.col-xl-3 {
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
    padding: 5px;
}

  }
      @media (max-width: 992px){
            .order:nth-of-type(1) { order: 1; }
      .summary_last{
          margin-bottom:10px;
      }
      .order_btn{
        text-align: left!important;

      }
  }
</style>
    <section>
      <div class="container">
        <div class="checkout">
          <div class="row">
            <div class="col-lg-6 col-md-6 col-12 order">
              <div class="section_main">
                  <h6 class="text-center my-3"><strong>অর্ডার কনফার্ম করতে আপনার নাম, ঠিকানা, মোবাইল নাম্বার লিখে অর্ডার কনফার্ম করুন বাটনে ক্লিক করুন</strong></h6>
              
                  <form class="form-default" data-toggle="validator" action="{{ route('checkout.without_auth') }}" role="form" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                              <label for="customer_name" class="form-label">আপনার নাম</label>
                              <input type="text" class="form-control" name="customer_name" id="customer_name" value="{{ Auth::user()->name ?? '' }}" required placeholder="আপনার নাম">
                            </div>
                            <div class="mb-3">
                              <label for="mobile_number" class="form-label">আপনার মোবাইল নাম্বার</label>
                              <input type="text" class="form-control" id="mobile_number" name="phone" placeholder="01900000000" required>
                            </div>
    
                            <div class="mb-3 row">
                                <label for="state" class="col-sm-2 col-form-label">জেলা</label>
                                <div class="col-sm-10">
                                    <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="state_id" required>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="City" class="col-sm-2 col-form-label">উপজেলা</label>
                                <div class="col-sm-10">
                                    {{-- <select class="form-control mb-3 aiz-selectpicker rounded-0" id="mySelect" onchange="getValue()" data-live-search="true" name="city_id" required>
                                    </select> --}}
                                    <select class="form-control mb-3 aiz-selectpicker rounded-0" id="mySelect" data-live-search="true" onchange="getValue()" name="city_id" required>

                                    </select>
                                </div>
                            </div>
                             <input type="hidden" name="shipping_charge" id="hidden_shipping_charge" value="0">
                             
                            <div class="mb-3">
                              <label for="full_address" class="form-label">আপনার সম্পূর্ন ঠিকানা</label>
                              <textarea class="form-control" id="full_address" rows="2" name="address" value="{{old('address')}}" required placeholder="আপনার সম্পূর্ন ঠিকানা"></textarea>
                            </div>
                            <!--<div class="mb-3 row">-->
                            <!--    <label for="state" class="col-sm-2 col-form-label">City</label>-->
                            <!--    <div class="col-sm-10">-->
                            <!--        <select class="form-control mb-3 aiz-selectpicker rounded-0" onchange="handleCityChange(this)" data-live-search="true" name="city_id" required>-->
                            <!--        </select>-->
                            <!--    </div>-->
                            <!--</div>-->
                        </div>
                    </div>
              
                    <div class="card-header p-4 mt-4 mb-3 border-bottom-0">
                        <h3 class="fs-16 fw-700 text-dark mb-0">
                            {{ translate('Select a payment option') }}
                        </h3>
                    </div>
                    <!-- Payment Options -->
                    <div class="text-center  pt-0">
                        <div class="row m-0">
                            <!-- Paypal -->
                            @if (get_setting('paypal_payment') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="paypal" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/paypal.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('Paypal') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!--Stripe -->
                            @if (get_setting('stripe_payment') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="stripe" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/stripe.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('Stripe') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- Mercadopago -->
                            @if (get_setting('mercadopago_payment') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="mercadopago" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/mercadopago.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('Mercadopago') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- sslcommerz -->
                            @if (get_setting('sslcommerz_payment') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="sslcommerz" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/sslcommerz.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('sslcommerz') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- instamojo -->
                            @if (get_setting('instamojo_payment') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="instamojo" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/instamojo.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('Instamojo') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- razorpay -->
                            @if (get_setting('razorpay') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="razorpay" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/rozarpay.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('Razorpay') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- paystack -->
                            @if (get_setting('paystack') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="paystack" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/paystack.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('Paystack') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- voguepay -->
                            @if (get_setting('voguepay') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="voguepay" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/vogue.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('VoguePay') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- payhere -->
                            @if (get_setting('payhere') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="payhere" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/payhere.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('payhere') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- ngenius -->
                            @if (get_setting('ngenius') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="ngenius" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/ngenius.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('ngenius') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- iyzico -->
                            @if (get_setting('iyzico') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="iyzico" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/iyzico.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('Iyzico') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- nagad -->
                            @if (get_setting('nagad') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="nagad" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/nagad.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('Nagad') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- bkash -->
                            @if (get_setting('bkash') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="bkash" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/bkash.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('Bkash') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- aamarpay -->
                            @if (get_setting('aamarpay') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="aamarpay" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/aamarpay.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('Aamarpay') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- authorizenet -->
                            @if (get_setting('authorizenet') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="authorizenet" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/authorizenet.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('Authorize Net') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- payku -->
                            @if (get_setting('payku') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="payku" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/payku.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('Payku') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- African Payment Getaway -->
                            @if (addon_is_activated('african_pg'))
                                <!-- flutterwave -->
                                @if (get_setting('flutterwave') == 1)
                                    <div class="col-6 col-xl-3 col-md-4">
                                        <label class="aiz-megabox d-block mb-3">
                                            <input value="flutterwave" class="online_payment"
                                                type="radio" name="payment_option" checked>
                                            <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                                <img src="{{ static_asset('assets/img/cards/flutterwave.png') }}"
                                                    class="img-fit mb-2">
                                                <span class="d-block text-center">
                                                    <span
                                                        class="d-block fw-500 fs-12">{{ translate('flutterwave') }}</span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                @endif
                                <!-- payfast -->
                                @if (get_setting('payfast') == 1)
                                    <div class="col-6 col-xl-3 col-md-4">
                                        <label class="aiz-megabox d-block mb-3">
                                            <input value="payfast" class="online_payment" type="radio"
                                                name="payment_option" checked>
                                            <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                                <img src="{{ static_asset('assets/img/cards/payfast.png') }}"
                                                    class="img-fit mb-2">
                                                <span class="d-block text-center">
                                                    <span
                                                        class="d-block fw-500 fs-12">{{ translate('payfast') }}</span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                @endif
                            @endif
                            <!--paytm -->
                            @if (addon_is_activated('paytm') && get_setting('paytm_payment') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="paytm" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/paytm.jpg') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('Paytm') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- toyyibpay -->
                            @if (addon_is_activated('paytm') && get_setting('toyyibpay_payment') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="toyyibpay" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/toyyibpay.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('ToyyibPay') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- myfatoorah -->
                            @if (addon_is_activated('paytm') && get_setting('myfatoorah') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="myfatoorah" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                            <img src="{{ static_asset('assets/img/cards/myfatoorah.png') }}"
                                                class="img-fit mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('MyFatoorah') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- khalti -->
                            @if (addon_is_activated('paytm') && get_setting('khalti_payment') == 1)
                                <div class="col-6 col-xl-3 col-md-4">
                                    <label class="aiz-megabox d-block mb-3">
                                        <input value="Khalti" class="online_payment" type="radio"
                                            name="payment_option" checked>
                                        <span class="d-block aiz-megabox-elem p-2">
                                            <img src="{{ static_asset('assets/img/cards/khalti.png') }}"
                                                class="img-fluid mb-2">
                                            <span class="d-block text-center">
                                                <span
                                                    class="d-block fw-500 fs-12">{{ translate('Khalti') }}</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            @endif
                            <!-- Cash Payment -->
                            @if (get_setting('cash_payment') == 1)
                                @php
                                    $digital = 0;
                                    $cod_on = 1;
                                    foreach ($carts as $cartItem) {
                                        $product = get_single_product($cartItem['product_id']);
                                        if ($product['digital'] == 1) {
                                            $digital = 1;
                                        }
                                        if ($product['cash_on_delivery'] == 0) {
                                            $cod_on = 0;
                                        }
                                    }
                                @endphp
                                @if ($digital != 1 && $cod_on == 1)
                                    <div class="col-6 col-xl-3 col-md-4">
                                        <label class="aiz-megabox d-block mb-3">
                                            <input value="cash_on_delivery" class="online_payment"
                                                type="radio" name="payment_option" checked>
                                            <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                                <img src="{{ static_asset('assets/img/cards/cod.png') }}"
                                                    class="img-fit mb-2">
                                                <span class="d-block text-center">
                                                    <span
                                                        class="d-block fw-500 fs-12">{{ translate('Cash on Delivery') }}</span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                @endif
                            @endif
                            @if (Auth::check())
                                <!-- Offline Payment -->
                                @if (addon_is_activated('offline_payment'))
                                    @foreach (get_all_manual_payment_methods() as $method)
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="{{ $method->heading }}" type="radio"
                                                    name="payment_option" class="offline_payment_option"
                                                    onchange="toggleManualPaymentData({{ $method->id }})"
                                                    data-id="{{ $method->id }}" checked>
                                                <span class="d-block aiz-megabox-elem rounded-0 p-2">
                                                    <img src="{{ uploaded_asset($method->photo) }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span
                                                            class="d-block fw-500 fs-12">{{ $method->heading }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach

                                    @foreach (get_all_manual_payment_methods() as $method)
                                        <div id="manual_payment_info_{{ $method->id }}" class="d-none">
                                            @php echo $method->description @endphp
                                            @if ($method->bank_info != null)
                                                <ul>
                                                    @foreach (json_decode($method->bank_info) as $key => $info)
                                                        <li>{{ translate('Bank Name') }} -
                                                            {{ $info->bank_name }},
                                                            {{ translate('Account Name') }} -
                                                            {{ $info->account_name }},
                                                            {{ translate('Account Number') }} -
                                                            {{ $info->account_number }},
                                                            {{ translate('Routing Number') }} -
                                                            {{ $info->routing_number }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                        </div>

                        <!-- Offline Payment Fields -->
                        @if (addon_is_activated('offline_payment'))
                            <div class="d-none mb-3 rounded border bg-white p-3 text-left">
                                <div id="manual_payment_description">

                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>{{ translate('Transaction ID') }} <span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control mb-3" name="trx_id"
                                            id="trx_id" placeholder="{{ translate('Transaction ID') }}"
                                            >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">{{ translate('Photo') }}</label>
                                    <div class="col-md-9">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                    {{ translate('Browse') }}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose image') }}
                                            </div>
                                            <input type="hidden" name="photo" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Wallet Payment -->
                        @if (Auth::check() && get_setting('wallet_system') == 1)
                            <div class="py-4 px-4 text-center bg-soft-warning mt-4">
                                <div class="fs-14 mb-3">
                                    <span class="opacity-80">{{ translate('Or, Your wallet balance :') }}</span>
                                    <span class="fw-700">{{ single_price(Auth::user()->balance) }}</span>
                                </div>
                                @if (Auth::user()->balance < $total)
                                    <button type="button" class="btn btn-secondary" disabled>
                                        {{ translate('Insufficient balance') }}
                                    </button>
                                @else
                                    <button type="button" onclick="use_wallet()" class="btn btn-primary fs-14 fw-700 px-5 rounded-0">
                                        {{ translate('Pay with wallet') }}
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Agree Box -->
                    <div class="pt-2  fs-14">
                        <label class="aiz-checkbox">
                            <input type="checkbox" required id="agree_checkbox" checked>
                            <span class="aiz-square-check"></span>
                            <span>{{ translate('I agree to the') }}</span>
                        </label>
                        <a href="{{ route('terms') }}" class="fw-700">{{ translate('terms and conditions') }}</a>,
                        <a href="{{ route('returnpolicy') }}" class="fw-700">{{ translate('return policy') }}</a> &
                        <a href="{{ route('privacypolicy') }}" class="fw-700">{{ translate('privacy policy') }}</a>
                    </div>
                    <div class="row align-items-center pt-3  mb-4">
                                <!-- Return to shop -->
                        <div class="col-lg-6 col-12 ">
                            <a href="{{ route('home') }}" class="btn btn-link fs-14 fw-700 px-0">
                                <i class="las la-arrow-left fs-16"></i>
                                {{ translate('Return to shop') }}
                            </a>
                        </div>
                        <!-- Complete Ordert -->
                        <div class="col-lg-6 col-12  text-right order_btn">
                            <button type="submit" class="btn btn-primary fs-14 fw-700 rounded-0 px-4">অর্ডার কনফার্ম  করুন</button>
                        </div>
                    </div>
                
                </form>
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12 order summary_last">
              <div class="section_main">
              <h6 class="text-center my-3"><strong>অর্ডার ইনফরমেশন</strong></h6>
                 <div class="mb-4" id="cart-summary">
                     {{-- cart view --}}
                     <div class="cart_section">
                        <style>
                         .table{
                              text-align: center;
                         }
                         .table th{
                                 text-align: center;
                                padding: 4px;
                         }
                            .table td {
                        padding: 0.25rem;
                        vertical-align: top;
                     
                    }
                    .check_btn .btn-sm.btn-icon {
                        padding: 0.24rem;
                        width: 30px;
                        height: 30px;
                    }
                            @media (max-width:767px){
                              .responsive{
                                  width: 600px;
                              }  
                            }  
                        </style>
                        @if( $carts && count($carts) > 0 )
                                <div  style="overflow-x:auto;">
                                  <table class="table table-bordered responsive">
                                      <thead>
                                        <tr>
                                            <th scope="col"style="width:40px"></th>
                                            <th scope="col"  style="width:80px">{{ translate('Product')}}</th>
                                            <th scope="col">{{ translate('Product Name')}}</th>
                                            <th scope="col">{{ translate('Size')}}</th>
                                            <th scope="col" style="width:100px">{{ translate('Price')}}</th>
                                            <th scope="col" style="width:60px">{{ translate('Qty')}}</th>
                                            <th scope="col" style="width:100px">{{ translate('Total Price')}}</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                       
                                        @php
                                            $total = 0;
                                            $final_total = 0;
                                            $coupon_descount = 0;
                                        @endphp
                    
                                        @foreach ($carts as $key => $cartItem)
                                            @php
                                                $product = get_single_product($cartItem['product_id']);
                                                $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
                                                // $total = $total + ($cartItem['price']  + $cartItem['tax']) * $cartItem['quantity'];
                                                $coupon_descount += $cartItem->discount * $cartItem['quantity'];
                                                $total = ($total + cart_product_price($cartItem, $product, false) * $cartItem['quantity']);
                                                $final_total = ($final_total + cart_product_price($cartItem, $product, false) * $cartItem['quantity']) - $coupon_descount;
                                                $product_name_with_choice = $product->getTranslation('name');
                                            @endphp
                                             <tr>
                                                 <td>
                                                    <a href="javascript:void(0)" onclick="removeFromCartView(event, {{ $cartItem['id'] }})" class="btn btn-icon btn-sm btn-soft-primary bg-soft-warning hov-bg-primary btn-circle">
                                                        <i class="las la-trash fs-16"></i>
                                                    </a>
                                                 </td>
                                                <td>
                                                    <span class=" ml-0">
                                                        <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                            class="img-fit size-60px"
                                                            alt="{{ $product->getTranslation('name')  }}"
                                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fs-14">{{ $product_name_with_choice }}</span>
                                                </td>
                                                <td>
                                                    <span class="fs-14">{{ $cartItem['variation'] }}</span>
                                                </td>
                                                <td>
                                                    <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Price')}}</span>
                                                    <span class="fw-500 fs-13">{{ cart_product_price($cartItem, $product, true, false) }}</span>
                                                    <!--<div>-->
                                                    <!--    <span class="fw-500 fs-14">Tax : </span>-->
                                                    <!--    <span class="fw-500 fs-14">{{ cart_product_tax($cartItem, $product) }}</span> -->
                                                    <!--</div>-->
                                                
                                                 </td>
                                                <td>
                                                    <div class="">
                                                        @if ($cartItem['digital'] != 1 && $product->auction_product == 0)
                                                            <div class="d-flex check_btn aiz-plus-minus ">
                                                                <button
                                                                    class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                                    type="button" data-type="plus"
                                                                    data-field="quantity[{{ $cartItem['id'] }}]">
                                                                    <i class="las la-plus"></i>
                                                                </button>
                                                                <input type="number" name="quantity[{{ $cartItem['id'] }}]"
                                                                    class=" border-0 text-center input-number"
                                                                    placeholder="1" value="{{ $cartItem['quantity'] }}"
                                                                    min="{{ $product->min_qty }}"
                                                                    max="{{ $product_stock->qty }}"
                                                                    onchange="updateQuantity({{ $cartItem['id'] }}, this)">
                                                                <button
                                                                    class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                                    type="button" data-type="minus"
                                                                    data-field="quantity[{{ $cartItem['id'] }}]">
                                                                    <i class="las la-minus"></i>
                                                                </button>
                                                            </div>
                                                        @elseif($product->auction_product == 1)
                                                            <span class="fw-500 fs-14">1</span>
                                                        @endif
                                                    </div>
                                                </td>
                                              
                                                <td>
                                                    <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Total')}}</span>
                                                    <span class="fw-600 fs-16 text-primary">{{ single_price(cart_product_price($cartItem, $product, false) * $cartItem['quantity']) }}</span>
                                                </td>
                                            </tr>
                                         @endforeach
                                         <tr>
                                             <td><span class="d-none" id="total_price">{{ $total }}</span></td>
                                             <td></td>
                                             <td></td>
                                             <td></td>
                                             <td></td>
                                             <td>Sub Total</td>
                                             <td class="fw-600 fs-16 text-primary">{{ single_price($total) }}</td>
                                         </tr>
                                         <tr>
                                             <td></td>
                                             <td></td>
                                             <td></td>
                                             <td></td>
                                             <td></td>
                                             <td>Shipping</td>
                                             <td class="fw-600 fs-16 text-primary"> ৳ <span id="shipping_charge">0</span>
                                             </td>
                                         </tr>
                                        <tr>
                                             <td></td>
                                             <td></td>
                                             <td></td>
                                             <td></td>
                                             <td></td>
                                             <td>Coupon</td>
                                             <td class="fw-600 fs-16 text-primary"> ৳ <span id="coupon_charge">{{ $coupon_descount ?? 0}}</span>
                                             </td>
                                         </tr>
                                        <tr>
                                             <td></td>
                                             <td></td>
                                             <td></td>
                                             <td></td>
                                             <td></td>
                                             <td>Payable Amount</td>
                                             <td class="fw-600 fs-16 text-primary">৳ <span id="payable_amount"> {{$final_total}}</span></td>
                                         </tr>
                                      </tbody>
                                    </table>
                                    
                               </div>
                        
                           
                        @else
                            <div class="row">
                                <div class="col-xl-8 mx-auto">
                                    <div class="border bg-white p-4">
                                        <!-- Empty cart -->
                                        <div class="text-center p-3">
                                            <i class="las la-frown la-3x opacity-60 mb-3"></i>
                                            <h3 class="h4 fw-700">{{translate('Your Cart is empty')}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                     {{-- cart view --}}
                    <div class="row mt-3">
                        <div class="col-6 text-center">
                            @guest
                                <strong>অ্যাকাউন্ট থাকলে লগিন করুন</strong> <a href="{{ route('user.login') }}" class="btn btn-primary btn-sm ml-2">login</a>
                            @endguest
                        </div>
                        <div class="col-6 text-center">
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#couponModal">
                              কূপন থাকলে এপ্লাই করুন
                            </button>
                        </div>
                    </div>
                 </div>
              </div>
            </div>
          </div>
        </div>
    </section>

    <!-- Your existing modal code -->
    <div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="couponModalLabel">কূপন এপ্লাই করুন</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('checkout.apply_coupon_code') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="code" class="form-label">কূপন</label>
                            <input type="text" class="form-control" name="code" id="code" value="{{ old('code') }}" required placeholder="coupon code">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                    </div>
                </form>
                <!--<div class="modal-body">-->
                <!--    <div class="mb-3">-->
                <!--        <label for="code" class="form-label">কূপন</label>-->
                <!--        <input type="text" class="form-control" name="code" id="code" value="{{old('code')}}" required placeholder="coupon code">-->
                <!--    </div>-->
                <!--    <div class="mb-3">-->
                <!--        <button type="button" class="btn btn-primary" id="applyButton" data-route="{{ route('checkout.apply_coupon_code') }}">Apply</button>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
        </div>
    </div>
    
    {{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        
        window.onload = function() {
            let country_id = 18;
    
            get_states(country_id);
        };
    
        function getValue() {
          var selectElement = document.getElementById("mySelect");
    
          var selectedValue = selectElement.value;
    
          if (selectedValue) {
            var url = '{{ url('shapping/charge/custom') }}' + '/' + selectedValue;
    
            $.ajax({
                type: "GET",
                url: url,
                success: function(data) {
                    $('#shipping_charge').text(data.cost);
                    // let shipping_charge = parseFloat($('#shipping_charge').text());
    
                    // Set the value to the hidden input field
                    $('#hidden_shipping_charge').val(data.cost);
                    grandTotal(data.cost);
                }
            });
         }
        }
        
        function grandTotal(charge) {
            let total_price = parseFloat(document.getElementById("total_price").innerText);
            let coupon_charge = parseFloat(document.getElementById("coupon_charge").innerText);
            let total = total_price + parseFloat(charge) - coupon_charge;
            
            $('#payable_amount').text(parseFloat(total));
        }
    </script> --}}


{{-- cart details --}}

<script type="text/javascript">
    AIZ.extra.plusMinus();
</script>
<script type="text/javascript">
    
    function removeFromCartView(e, key) {
        e.preventDefault();
        removeFromCart(key);
    }

    function updateQuantity(key, element) {
        $.post('{{ route('cart.updateQuantity') }}', {
            _token: AIZ.data.csrf,
            id: key,
            quantity: element.value
        }, function(data) {
            updateNavCart(data.nav_cart_view, data.cart_count);
            $('#cart-summary').html(data.cart_view);
        });
    }
</script>
{{-- cart details --}}


@endsection
@section('modal')
    <!-- Address Modal -->
    {{-- @include('frontend.partials.address_modal') --}}
@endsection
@section('script')

    <script type="text/javascript">
    
        function display_option(key){

        }
        function show_pickup_point(el,type) {
        	var value = $(el).val();
        	var target = $(el).data('target');

        	if(value == 'home_delivery' || value == 'carrier'){
                if(!$(target).hasClass('d-none')){
                    $(target).addClass('d-none');
                }
                $('.carrier_id_'+type).removeClass('d-none');
        	}else{
        		$(target).removeClass('d-none');
        		$('.carrier_id_'+type).addClass('d-none');
        	}
        }

        //  address checking

        window.onload = function() {
            let country_id = 18;
    
            get_states(country_id);
        };

        $(document).on('change', '[name=state_id]', function() {
            var state_id = $(this).val();
            get_city(state_id);
        });

        function get_states(country_id) {
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-state')}}",
                type: 'POST',
                data: {
                    country_id  : country_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="state_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }


        function get_city(state_id) {
            $('[name="city"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-city')}}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="city_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        // shipping cost

        function getValue() {
          var selectElement = document.getElementById("mySelect");
    
          var selectedValue = selectElement.value;
    
          if (selectedValue) {
            var url = '{{ url('shapping/charge/custom') }}' + '/' + selectedValue;
    
            $.ajax({
                type: "GET",
                url: url,
                success: function(data) {
                    $('#shipping_charge').text(data.cost);
                    // let shipping_charge = parseFloat($('#shipping_charge').text());
    
                    // Set the value to the hidden input field
                    $('#hidden_shipping_charge').val(data.cost);
                    grandTotal(data.cost);
                }
            });
         }
        }
        
        function grandTotal(charge) {
            let total_price = parseFloat(document.getElementById("total_price").innerText);
            let coupon_charge = parseFloat(document.getElementById("coupon_charge").innerText);
            let total = total_price + parseFloat(charge) - coupon_charge;
            
            $('#payable_amount').text(parseFloat(total));
        }

    </script>

    {{-- @if (get_setting('google_map') == 1)
        @include('frontend.partials.google_map')
    @endif --}}

@endsection
