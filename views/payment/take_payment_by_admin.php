<div class="take-payment-admin light-card">
<form id="paymentForm" name="paymentForm" onsubmit="return validatePaymentForm()" method="post" action="<?= url("Payment@submitPaymentMode"); ?>" >
    <input type="hidden" name="price_total" value="{{$price_total }}">
    <input type="hidden" name="payment_user_id" value="{{ $payment_user->id }}">
    @if ( $user->role =='user' )
        <input type="hidden" name="payment_option" value="by_user">
    @else
        <input type="hidden" name="payment_option" value="by_admin">
    @endif
    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
    <div class="form-group">
        <div class="payment-detail">
            <div class="row total">
                <div class="col-xs-8">
                    User name : <label class="color-red">{{$payment_user->fname}} {{$payment_user->lname}}</label>
                </div>
            </div>
            <div class="divider"></div>
            <?php include "fee_check.php" ?>

            <div class="admin-t-payment-fee">
                <div class="fee-row row total">
                    <div class="fee-col1 col-md-4">Total Payment Amount:</div>
                    <div class="fee-col2 col-md-2">&nbsp;</div>
                    <div class="fee-col3 col-md-3">&nbsp;</div>
                    <div class="fee-col4 col-md-2 color-red">$<label id="price_total_label"></label></div>
                </div>
            </div>
        </div>
    </div>
    <div class="admin-t-payment-choose-p">
    	<div style="margin-bottom: 15px">
	        Please Choose payment
	    </div>
        @if ( $payment_user->holding_balance>0 )
        <button type="submit" name="payment_type" class="btn btn-primary"
                value="Holding">
            Pre-payment
        </button>
        @endif
	    <button type="submit" name="payment_type" class="btn btn-primary"
	            value="Credit Card">
	        Credit Card
	    </button>
        @if ( $user->role !='user' )
	    <button type="submit" name="payment_type" class="btn btn-primary"
	            value="Cash">
	        Cash
	    </button>
	    <button type="submit" name="payment_type" class="btn btn-primary"
	            value="Check">
	        Check
	    </button>
        @endif
<!--        @if ( $payment_user->is_subscribe )-->
<!--        <button type="submit" name="payment_type" class="btn btn-primary"-->
<!--                value="Unsubscribe">-->
<!--            Cancel Autopay-->
<!--        </button>-->
<!--        @else-->
<!--        <button type="submit" name="payment_type" class="btn btn-primary"-->
<!--                value="Subscribe">-->
<!--            Autopay-->
<!--        </button>-->
<!--        @endif-->
    </div>
</form>
</div>
