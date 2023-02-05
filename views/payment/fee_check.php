<div class="admin-t-payment-fee">
    <div class="fee-row row">
        <div class="fee-col1 col-md-4"><strong>Fee Type</strong></div>
        <div class="fee-col2 col-md-2"><strong>Fee Amount</strong></div>
        <div class="fee-col3 col-md-3"><strong>Due</strong></div>
        @if($user->role != 'user')
        <div class="fee-col4 col-md-2"><strong class="color-red">&nbsp;&nbsp;&nbsp;&nbsp;Change</strong></div>
        @endif
    </div>
    <div class="fee-row row">
        <div class="fee-col1 col-md-4">Security Deposit</div>
        <div class="fee-col2 col-md-2 border-top border-left">${{ $order->security_fee }}</div>
        <div class="fee-col3 col-md-3 border-top border-left border-right">
            <input type="checkbox" name="security_check" checked>
            ${{ $order->security_due_status }}
            @if($employer->security_pay == 1)
            <label class="color-red" style="margin:0">&nbsp;&nbsp;Employer Paid</label>
            @endif
            @if($payment_user->security_deposit != $companies->security)
            <label class="color-red" style="margin:0">&nbsp;&nbsp;Fee Changed</label>
            @endif
        </div>
        @if($user->role != 'user')
        <div class="fee-col4 col-md-2">
            <label class="float-left">$</label>
            <input type="number" class="fee-change col-md-8" id="security_amount" name="security_amount" min="0" max="{{$order->security_due_status}}" value="{{$order->security_due_status}}" step="any" >
        </div>
        @else
            <input type="hidden" id="security_amount" name="security_amount" value="{{$order->security_due_status}}">
        @endif
    </div>
    <div class="fee-row row">
        <div class="fee-col1 col-md-4">Room Fee( weekly rate : ${{$student_room_fee['week_fee']}})</div>
        <div class="fee-col2 col-md-2 border-top border-left">${{ $order->room_fee }}</div>
        <div class="fee-col3 col-md-3 border-top border-left border-right">
            <input type="checkbox" name="room_check" checked>
            ${{ $order->room_due_status }}
            @if($employer->room_pay == 1)
            <label class="color-red" style="margin:0">&nbsp;&nbsp;Employer Paid</label>
            @endif
            @if($payment_user->weekly_rate != $companies->weekly)
            <label class="color-red" style="margin:0">&nbsp;&nbsp;Fee Changed</label>
            @endif
        </div>

        @if($user->role != 'user')
        <div class="fee-col4 col-md-2"><label class="float-left">$</label>
            <input type="number" class="fee-change col-md-8" id="room_amount" name="room_amount" min="0" max="{{$order->room_due_status}}" value="{{$order->room_due_status}}" step="any">
        </div>
        @else
        <input type="hidden" id="room_amount" name="room_amount" value="{{$order->room_due_status}}">
        @endif
    </div>

    <div class="fee-row row">
        <div class="fee-col1 col-md-4">Administration Fee</div>
        <div class="fee-col2 col-md-2 border-top border-left">${{ $order->administration_fee }}</div>
        <div class="fee-col3 col-md-3 border-top border-left border-right">
            <input type="checkbox" name="administration_check" checked>
            ${{ $order->administration_due_status }}
            @if($employer->administration_pay == 1)
            <label class="color-red" style="margin:0">&nbsp;&nbsp;Employer Paid</label>
            @endif
        </div>
        @if($user->role != 'user')
        <div class="fee-col4 col-md-2"><label class="float-left">$</label>
            <input type="number" class="fee-change col-md-8" id="administration_amount" name="administration_amount" min="0" step="any" max="{{$order->administration_due_status}}" value="{{$order->administration_due_status}}" >
        </div>
        @else
        <input type="hidden" id="administration_amount" name="administration_amount" value="{{$order->administration_due_status}}">
        @endif
    </div>
    <div class="fee-row row">
        <div class="fee-col1 col-md-4">Laundry Fee</div>
        <div class="fee-col2 col-md-2 border-top border-left border-bottom">${{ $order->laundry_fee }}</div>
        <div class="fee-col3 col-md-3 border-top border-left border-right border-bottom">
            <input type="checkbox" name="laundry_check" checked>
            ${{ $order->laundry_due_status }}
            @if($employer->laundry_pay == 1)
            <label class="color-red" style="margin:0">&nbsp;&nbsp;Employer Paid</label>
            @endif
        </div>
        @if($user->role != 'user')
        <div class="fee-col4 col-md-2"><label class="float-left">$</label>
            <input type="number" class="fee-change col-md-8" id="laundry_amount" name="laundry_amount" min="0" max="{{$order->laundry_due_status}}" value="{{$order->laundry_due_status}}" step="any">
        </div>
        @else
        <input type="hidden" id="laundry_amount" name="laundry_amount" value="{{$order->laundry_due_status}}">
        @endif
    </div>
    <div class="divider"></div>
    <div class="fee-row row">
        <div class="fee-col1 col-md-4">Holding</div>
        <div class="fee-col2 col-md-2">&nbsp;</div>
        <div class="fee-col3 col-md-3">&nbsp;</div>
        <div class="fee-col4 col-md-2"><label class="float-left">$</label>
            <input type="number" class="fee-change col-md-8" id="holding_amount" name="holding_amount"  step="any" min="0" value="0">
        </div>
    </div>
</div>
<script src="<?=url("");?>assets/js/jquery-3.2.1.min.js"></script>
<script>
    let total_fee = 0;
    $(document).ready(function(){
        let room_fee = parseInt($('#room_amount').val());
        let security_fee = parseInt($('#security_amount').val());
        let laundry_fee = parseInt($('#laundry_amount').val());
        let administration_fee = parseInt($('#administration_amount').val());
        let holding_amount = parseInt($('#holding_amount').val());
        update_total();

        $(".fee-change").on('keyup change', function (){
            let fee_amount_id = $(this).attr('id');
            let fee_amount_val = parseInt($(this).val());
            if(fee_amount_id == "room_amount")
                room_fee = fee_amount_val;
            if(fee_amount_id == "security_amount")
                security_fee = fee_amount_val;
            if(fee_amount_id == "laundry_amount")
                laundry_fee = fee_amount_val;
            if(fee_amount_id == "administration_amount")
                administration_fee = fee_amount_val;
            if(fee_amount_id == "holding_amount")
                holding_amount = fee_amount_val;

            update_total();
        });

        $("[name='security_check']").change(function(){
            if($(this).is(':checked')){
                security_fee = parseInt($('#security_amount').val());
                $('#security_amount').removeAttr("disabled");
            }
            else{
                security_fee = 0;
                $('#security_amount').attr("disabled", "disabled");
            }
            update_total();
        });
        $("[name='room_check']").change(function(){
            if($(this).is(':checked')){
                room_fee = parseInt($('#room_amount').val());
                $('#room_amount').removeAttr("disabled");
            }
            else{
                room_fee = 0;
                $('#room_amount').attr("disabled", "disabled");
            }
            update_total();
        });
        $("[name='administration_check']").change(function(){
            if($(this).is(':checked')){
                administration_fee = parseInt($('#administration_amount').val());
                $('#administration_amount').removeAttr("disabled");
            }
            else{
                administration_fee = 0;
                $('#administration_amount').attr("disabled", "disabled");
            }
            update_total();
        });
        $("[name='laundry_check']").change(function(){
            if($(this).is(':checked')){
                laundry_fee = parseInt($('#laundry_amount').val());
                $('#laundry_amount').removeAttr("disabled");
            }
            else{
                laundry_fee = 0;
                $('#laundry_amount').attr("disabled", "disabled");
            }
            update_total();
        });


        function update_total() {
            total_fee = room_fee+security_fee+laundry_fee+administration_fee+holding_amount;
            if (window.location.href.indexOf("checkin") != -1){
                $("#total_price").html(total_fee);
            }
            else{
                $("#price_total_label").html(total_fee);
            }
            $("[name='price_total']").val(total_fee);
        }
    });

    // $(function () {
    //     $( "input" ).change(function() {
    //         var max = parseInt($(this).attr('max'));
    //         var min = parseInt($(this).attr('min'));
    //         if ($(this).val() > max)
    //         {
    //             $(this).val(max);
    //         }
    //         else if ($(this).val() < min)
    //         {
    //             $(this).val(min);
    //         }
    //     });
    // });

    function validatePaymentForm(){
        if ($(document.activeElement).val()=='Holding'){
            let holdingBalance={{$payment_user->holding_balance}};
            if (total_fee>holdingBalance){
                swal("Insufficient Holding balance", "Holding Balance is $"+holdingBalance+". But you are trying to pay $"+total_fee+".", "error");
                return false;
            }
        }
    }

    function onHoldingBtn() {
        let holdingBalance={{$payment_user->holding_balance}};
        if (total_fee>holdingBalance){
            swal("Insufficient Holding balance", "Holding Balance is $"+holdingBalance+". But you are trying to pay $"+total_fee+".", "error");
        }
        else{
            let form=document.getElementById('paymentForm');
            let input = document.createElement('input');//prepare a new input DOM element
            input.setAttribute('name', 'payment_type');//set the param name
            input.setAttribute('value', 'Holding');//set the value
            input.setAttribute('type', 'hidden')//set the type, like "hidden" or other

            form.appendChild(input);//append the input to the form
            form.submit();
        }
    }
</script>