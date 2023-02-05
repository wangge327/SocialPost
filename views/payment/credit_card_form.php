<!--payments/credit_card_form.php-->
<div class="payment col-sm-5 credit-card-form">
    <div class="row">
        <form action="<?= url("CC@submitCard"); ?>" method="post" style="padding: 15px">

        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
        <input type="hidden" name="payment_user_id" value="{{ $payment_user->id }}">
        <input type="hidden" name="parentPage" value="{{ $parentPage }}">
        <input type="hidden" name="payment_option" value="{{ $_POST['payment_option'] }}">
        <input type="hidden" name="price_total" value="{{$price_total}}">
        <input type="hidden" name="payment_type" value="{{$_POST['payment_type']}}">

        <input type="hidden" name="security_amount" value="{{$_POST['security_amount']}}">
        <input type="hidden" name="room_amount" value="{{$_POST['room_amount']}}">
        <input type="hidden" name="administration_amount" value="{{$_POST['administration_amount']}}">
        <input type="hidden" name="laundry_amount" value="{{$_POST['laundry_amount']}}">
        <input type="hidden" name="holding_amount" value="{{$_POST['holding_amount']}}">

        @if( $page == "Subscribe")
        <div class="form-group">
            <div class="payment-detail">
                <div class="row total">
                    <div class="col-xs-6">
                        Payment Amount<br>(Weekly Room Fee)
                    </div>
                    <div class="col-xs-6">
                        ${{$weekly_amount}}
                        <input type="hidden" name="weekly_amount" value="{{$weekly_amount}}">
                        <input type="hidden" name="plan_id" value="{{$plan_id}}">
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="billing-information">
            <div class="form-group" style="font-size: 18px;font-weight: bold;">Billing Information</div>
            <div class="form-group" style="display: inline-block;">
                <div class="col-md-6">
                    <label>First Name</label>
                    <input type="text" class="form-control" name="fname" value="{{ $payment_user->fname }}" placeholder="First Name" data-parsley-required="true" >
                </div>

                <div class="col-md-6">
                    <label>Last Name</label>
                    <input type="text" class="form-control" name="lname" value="{{ $payment_user->lname }}" placeholder="Last Name" data-parsley-required="true">
                </div>
            </div>
            <div class="form-group" style="display: inline-block;width: 100%;" >
                <div class="col-md-12">
                    <label>Address</label>
                    <input type="text" class="form-control" name="address" placeholder="Address" value="{{ $payment_user->address }}">
                </div>
            </div>
            <div class="form-group" style="display: inline-block;">
                <div class="col-md-6">
                    <label>City</label>
                    <input type="text" class="form-control" name="city" value="{{ $payment_user->city }}" placeholder="City" data-parsley-required="true" >
                </div>

                <div class="col-md-6">
                    <label>State</label>
                    <input type="text" class="form-control" name="state" value="{{ $payment_user->state }}" placeholder="State" data-parsley-required="true">
                </div>
            </div>
            <div class="form-group" style="display: inline-block;">
                <div class="col-md-6">
                    <label>Zip</label>
                    <input type="text" class="form-control" name="zip" value="{{ $payment_user->zip }}" placeholder="Zip" data-parsley-required="true" >
                </div>

                <div class="col-md-6">
                    <label>Country</label>
                    <input type="text" class="form-control" name="country" value="{{ $payment_user->country }}" placeholder="Country" data-parsley-required="true">
                </div>
            </div>
            <div class="form-group" style="display: inline-block;">
                <div class="col-md-6">
                    <label>Phone</label>
                    <input type="text" class="form-control" name="phone" value="{{ $payment_user->phone }}" placeholder="Phone" data-parsley-required="true" >
                </div>

                <div class="col-md-6">
                    <label>Email</label>
                    <input type="text" class="form-control" name="email" value="{{ $payment_user->email }}" placeholder="Email" data-parsley-required="true">
                </div>
            </div>
        </div>

        <div class="card-information">
            <img src="<?= url(""); ?>assets/images/2checkout-cards.png" alt="Credit Card" class="img-responsive" style="margin: auto;margin-bottom: 30px">
            <div class="form-group" style="font-size: 18px;font-weight: bold;">Card Information</div>
            <div class="form-group" style="display: inline-block;width: 100%;" >
                <div class="col-md-12">
                    <label for="cardNumber">Card Number</label>
                    <input type="text" class="form-control" id="cardNumber" name="ccnumber" placeholder="" required="">
                </div>
            </div>
            <div class="form-group" style="display: inline-block;width: 100%;">
                <div class="col-md-12">
                    <label data-toggle="tooltip" title=""
                           data-original-title="3 digits code on back side of the card">CVV </label>
                    <input type="number" class="form-control" id="cvv" required="" name="cvv">
                </div>
            </div>
            <div class="form-group" style="display: inline-block;width: 100%;">
                <div class="col-md-12">
                    <label>Expiration Date</label>
                    <select name="ccexpmm">
                        <option value="01">January</option>
                        <option value="02">February </option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>

                    <select name="ccexpyy">
                        <option value="21"> 2021</option>
                        <option value="22"> 2022</option>
                        <option value="23"> 2023</option>
                        <option value="24"> 2024</option>
                        <option value="25"> 2025</option>
                        <option value="26"> 2026</option>
                        <option value="27"> 2027</option>
                    </select>
                </div>
            </div>
            <div class="form-group" id="credit_cards">
                <button type="submit" name="submit" class="btn btn-primary btn-lg pull-right" id="confirm-purchase" style="display:block"><i class="fas fa-fw fa-credit-card"></i> Pay
                </button>
            </div>
        </div>
    </form>
    </div>
</div>
