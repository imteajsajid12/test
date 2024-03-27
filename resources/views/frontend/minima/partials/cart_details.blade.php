{{-- <div class="container">
    @if( $carts && count($carts) > 0 )
        <div class="row">
            <div class="col-xxl-8 col-xl-10 mx-auto">
                <div class="border bg-white p-3 p-lg-4 text-left">
                    <div class="mb-4">
                        <!-- Headers -->
                        <div class="row gutters-5 d-none d-lg-flex border-bottom mb-3 pb-3 text-secondary fs-12">
                            <div class="col col-md-1 fw-600">{{ translate('Qty')}}</div>
                            <div class="col-md-5 fw-600">{{ translate('Product')}}</div>
                            <div class="col fw-600">{{ translate('Price')}}</div>
                            <div class="col fw-600">{{ translate('Tax')}}</div>
                            <div class="col fw-600">{{ translate('Total')}}</div>
                            <div class="col-auto fw-600">{{ translate('Remove')}}</div>
                        </div>
                        <!-- Cart Items -->
                        <ul class="list-group list-group-flush">
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($carts as $key => $cartItem)
                                @php
                                    $product = get_single_product($cartItem['product_id']);
                                    $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
                                    // $total = $total + ($cartItem['price']  + $cartItem['tax']) * $cartItem['quantity'];
                                    $total = $total + cart_product_price($cartItem, $product, false) * $cartItem['quantity'];
                                    $product_name_with_choice = $product->getTranslation('name');
                                    if ($cartItem['variation'] != null) {
                                        $product_name_with_choice = $product->getTranslation('name').' - '.$cartItem['variation'];
                                    }
                                @endphp
                                <li class="list-group-item px-0">
                                    <div class="row gutters-5 align-items-center">
                                        <!-- Quantity -->
                                        <div class="col-md-1 col order-1 order-md-0">
                                            @if ($cartItem['digital'] != 1 && $product->auction_product == 0)
                                                <div class="d-flex flex-column align-items-start aiz-plus-minus mr-2 ml-0">
                                                    <button
                                                        class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                        type="button" data-type="plus"
                                                        data-field="quantity[{{ $cartItem['id'] }}]">
                                                        <i class="las la-plus"></i>
                                                    </button>
                                                    <input type="number" name="quantity[{{ $cartItem['id'] }}]"
                                                        class="col border-0 text-left px-0 flex-grow-1 fs-14 input-number"
                                                        placeholder="1" value="{{ $cartItem['quantity'] }}"
                                                        min="{{ $product->min_qty }}"
                                                        max="{{ $product_stock->qty }}"
                                                        onchange="updateQuantity({{ $cartItem['id'] }}, this)" style="padding-left:0.75rem !important;">
                                                    <button
                                                        class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                        type="button" data-type="minus"
                                                        data-field="quantity[{{ $cartItem['id'] }}]">
                                                        <i class="las la-minus"></i>
                                                    </button>
                                                </div>
                                            @elseif($product->auction_product == 1)
                                                <span class="fw-700 fs-14">1</span>
                                            @endif
                                        </div>
                                        <!-- Product Image & name -->
                                        <div class="col-md-5 d-flex align-items-center mb-2 mb-md-0">
                                            <span class="mr-2 ml-0">
                                                <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                    class="img-fit size-70px"
                                                    alt="{{ $product->getTranslation('name')  }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </span>
                                            <span class="fs-14">{{ $product_name_with_choice }}</span>
                                        </div>
                                        <!-- Price -->
                                        <div class="col-md col-4 order-2 order-md-0 my-3 my-md-0">
                                            <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Price')}}</span>
                                            <span class="fw-700 fs-14">{{ cart_product_price($cartItem, $product, true, false) }}</span>
                                        </div>
                                        <!-- Tax -->
                                        <div class="col-md col-4 order-3 order-md-0 my-3 my-md-0">
                                            <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Tax')}}</span>
                                            <span class="fw-700 fs-14">{{ cart_product_tax($cartItem, $product) }}</span>
                                        </div>
                                        <!-- Total -->
                                        <div class="col-md col-5 order-4 order-md-0 my-3 my-md-0">
                                            <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Total')}}</span>
                                            <span class="fw-700 fs-16 text-primary">{{ single_price(cart_product_price($cartItem, $product, false) * $cartItem['quantity']) }}</span>
                                        </div>
                                        <!-- Remove From Cart -->
                                        <div class="col-md-auto col-6 order-5 order-md-0 text-right">
                                            <a href="javascript:void(0)" onclick="removeFromCartView(event, {{ $cartItem['id'] }})" class="btn btn-icon btn-sm btn-soft-primary bg-soft-secondary-base hov-bg-primary btn-circle">
                                                <i class="las la-trash fs-16"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Subtotal -->
                    <div class="px-0 py-2 mb-4 border-top d-flex justify-content-between">
                        <span class="opacity-60 fs-14">{{translate('Subtotal')}}</span>
                        <span class="fw-700 fs-16">{{ single_price($total) }}</span>
                    </div>
                    <div class="row align-items-center">
                        <!-- Return to shop -->
                        <div class="col-md-6 text-center text-md-left order-1 order-md-0">
                            <a href="{{ route('home') }}" class="btn btn-link fs-14 fw-700 px-0">
                                <i class="las la-arrow-left fs-16"></i>
                                {{ translate('Return to shop')}}
                            </a>
                        </div>
                        <!-- Continue to Shipping -->
                        <div class="col-md-6 text-center text-md-right">
                            @if(Auth::check())
                                <a href="{{ route('checkout.shipping_info') }}" class="btn btn-primary fs-14 fw-700 rounded-0 px-4">
                                    {{ translate('Continue to Shipping')}}
                                </a>
                            @else
                                <button class="btn btn-primary fs-14 fw-700 rounded-0 px-4" onclick="showLoginModal()">{{ translate('Continue to Shipping')}}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
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

<script type="text/javascript">
    AIZ.extra.plusMinus();
</script> --}}




{{-- new cart details --}}

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

