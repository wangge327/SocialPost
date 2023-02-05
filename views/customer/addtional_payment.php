<div class="modal-header">
    <h4 class="modal-title">Additional Payment</h4>
</div>
<div class="modal-body" style="height: 100%">
    <label>Student Name: {{ $student->fname }} {{ $student->lname }}</label>
    <hr>
    <label>Please make payment for increase cash.</label>
    <hr>
    <div class="form-group">
        The amount for payment : <span class="color-red">${{$amount}}</span>
    </div>


    <input type="hidden" name="csrf-token" value="{{ csrf_token(); }}"/>
    <input type="hidden" name="user_id" value="{{ $student->id }}"/>
    <input type="hidden" name="order_id" value="{{ $order->id }}"/>
    <input type="hidden" name="amount" value="{{ $amount }}"/>
    <input type="hidden" name="payment_option" value="cash">
    <hr>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Cash</button>
        <input type="button" class="btn btn-primary" id="credit_button" value="Credit Card">
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#credit_button").click(function(){
            $("#addtional_payment_form").attr('action', "<?=url('Checkout@addtionalPaymentCreditCard');?>");
            $("#addtional_payment_form").removeClass( "simcy-form" );
            $("#addtional_payment_form").submit();
        });
    });
</script>
