{{ view("includes/head", $data); }}
<body>
{{ view("includes/header", $data); }}
{{ view("includes/sidebar", $data); }}

<div class="content">
    <?php include "profile_header.php" ?>
    <div class="row">
        <div class="col-md-8 ">
            <?php include "profile_info.php" ?>

        </div>
        <div class="col-md-4">
            <?php include "profile_right.php" ?>
            <div class="bg-white padding-10" style="margin-top: 10px">

                <div class="row text-align-center margin-0">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#view_balance_history" data-backdrop="static" data-keyboard="false">Balance History</button>
                    <a href="{{url('PrintController@balanceHistoryPrint').$student->id}}" id="printBtn" class="btn btn-success" >Print</a>
                </div>

                <div class="row text-align-center margin-0" style="margin-top:10px">
                    @if($student->balance <= 0)
                    <a class="send-to-server-click btn btn-primary" data="customerid:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Payment@take_payment");?>" warning-title="Are you sure?" warning-message="This student has already paid full payment!" warning-button="Make Payment" loader="true" type="form" href="">Make Payment</a>
                    @else
                        <a class="a-form-post btn btn-primary" data="customerid:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Payment@take_payment");?>" href="">Make Payment</a>
                    @endif
                </div>

                @if($student->status != 'Terminated')
                <div class="row text-align-center margin-0" style="margin-top:10px">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#add-amount-balance" data-backdrop="static" data-keyboard="false">Add Amount to Student Balance</button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit User Account-->
    <div class="modal fade" id="edit" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Profile</h4>
                </div>
                <form class="simcy-form" id="edit-customer-form" action="<?=url("Customer@updateDashboardUser");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="customerid" value="{{ $student->id }}">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                    <div class="modal-body">
                        <p>Fill in Profile informations.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" name="fname" value="{{ $student->fname }}" placeholder="First Name" data-parsley-required="true" >
                                </div>
                                <div class="col-md-6">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" name="lname" value="{{ $student->lname }}" placeholder="Last Name" data-parsley-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Address</label>
                                    <input type="text" class="form-control" name="address" value="{{ $student->address }}" placeholder="Address" >
                                </div>

                                <div class="col-md-6">
                                    <label>City</label>
                                    <input type="text" class="form-control" name="city" value="{{ $student->city }}" placeholder="City">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>State</label>
                                    <input type="text" class="form-control" name="state" value="{{ $student->state }}" placeholder="state">
                                </div>
                                <div class="col-md-6">
                                    <label>Country</label>
                                    <input type="text" class="form-control" name="country"  value="{{ $student->country }}" placeholder="Country" data-parsley-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Email</label>
                                    <input type="text" class="form-control" name="email" value="{{ $student->email }}" placeholder="Email" data-parsley-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Phone number</label><br>
                                    <select class="form-control phone_country" name="phone_country">
                                        <option value="US"
                                        @if($student->phone_country == 'US')
                                            selected
                                        @endif
                                        >US +1</option>
                                        @foreach($phone_code as $each_phone_code)
                                        <option value="{{$each_phone_code->iso}}"
                                        @if($student->phone_country == $each_phone_code->iso)
                                            selected
                                        @endif
                                        >
                                            {{$each_phone_code->iso}} +{{$each_phone_code->phonecode}}
                                        </option>
                                        @endphp
                                    </select>
                                    <input type="number" class="form-control" name="phone" placeholder="Phone number" style="width: 64%" value="{{$student->phone}}">
                                </div>
                                <div class="col-md-6">
                                    <label>Pin code</label>
                                    <input type="text" class="form-control" name="pin" value="{{ $student->pin }}" placeholder="Pin code" data-parsley-required="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Profile</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Edit Specific Weekly Rate-->
    <div class="modal fade" id="specific-rate" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title">Edit Specific Weekly Rate</h4>
                </div>
                <form class="simcy-form" id="edit-customer-form" action="<?=url("Customer@updateSpecificWeeklyRate");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="customerid" value="{{ $student->id }}">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                    <div class="modal-body" style="height: 150px;">
                        <p>Enter Specific Weekly Rate.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Weekly Rate</label>
                                    <input type="number" class="form-control" name="weekly" value="{{ $student->weekly_rate }}" placeholder="Weekly Rate" data-parsley-required="true" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Rate</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Change Lease Start -->
    <div class="modal fade" id="change-lease-start" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title">Change Arrived Date</h4>
                </div>
                <form class="simcy-form" id="edit-customer-form" action="<?=url("Customer@updateLeaseStartDate");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="customerid" value="{{ $student->id }}">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                    <div class="modal-body" style="height: 150px;">
                        <p>Please choose date for lease start.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Lease Start</label>
                                    <input type="text" class="form-control" id="lease_start" name="lease_start" value="{{ $student->lease_start }}" placeholder="Lease Start" data-parsley-required="true" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Date</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Add Amount To Student Balance -->
    <div class="modal fade" id="add-amount-balance" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title">Add Amount To Student Balance</h4>
                </div>
                <form class="simcy-form" id="edit-customer-form" action="<?=url("Customer@addAmountToBalance");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="payment_user_id" value="{{ $student->id }}">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="payment_option" value="by_admin">
                    <div class="modal-body" style="height: 270px;">
                        <p>Enter Amount for adding balance.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Amount</label>
                                    <input type="number" class="form-control" name="price_total" placeholder="Amount for adding Balance" data-parsley-required="true" >
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Note</label>
                                    <textarea class="form-control" name="note_to_balance" data-parsley-required="true" rows="4" ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Rate</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <!-- Send Email -->
    <div class="modal fade" id="send-email" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title">Send Email to Student</h4>
                </div>
                <form class="simcy-form" id="edit-customer-form" action="<?=url("Customer@sendCustomEmail");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />

                    <div class="modal-body">
                        <p>Please Choose Email Template or Custom Email</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="checkbox" class="switch" value="1" id="email_switch"/>
                                    <label class="form-check-label">Custom Email</label>
                                    <input type="hidden" name="email_switch" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="form-group email-template-area">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Email Template</label>
                                    <select class="form-control" name="email_template_id">
                                        @foreach($email_templates as $email_template)
                                        <option value="{{$email_template->id}}">{{$email_template->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="custom-email-area">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Title</label>
                                        <input type="text" class="form-control" name="mail_title" placeholder="Email title" >
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Content</label>
                                        <textarea class="form-control" name="mail_content" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- View ID | take picture of I’d-->
    <div id="view_id_dlg" title="Camera" style="display: none;">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">View ID | take picture of I’d</h4>
                </div>

            </div>
        </div>
    </div>

    <!--View Balance History dialog -->
    <div class="modal fade" id="view_balance_history" role="dialog" style="background-color: #fff;">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog" style="width: 80%!important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">View Balance History</h4>
                </div>
                <div class="modal-body">
                    <div class="row padding-15">
                        <div class="col-md-3 padding-15 bal-dlg-col border-top border-left border-bottom">
                            <label>Total Balance Owed</label><br>
                            <span>$<span>{{$student->balance}}</span></span>
                        </div>
                        <div class="col-md-3 padding-15 bal-dlg-col border-top border-left border-bottom">
                            <label>Prepayments</label><br>
                            <span>$<span>{{$student->holding_balance}}</span></span>
                        </div>
<!--                        <div class="col-md-3 padding-15 bal-dlg-col border-top border-left border-bottom">-->
<!--                            <label>Total Unpaid</label><br>-->
<!--                            <span>$<span>{{$student->unpaid}}</span></span>-->
<!--                        </div>-->
                        <div class="col-md-3 padding-15 bal-dlg-col border-top border-left border-bottom border-right">
                            <label>Deposit Held</label><br>
                            <span>$<span>{{$order->security_fee-$order->security_due_status}}</span></span>
                        </div>
                    </div>
                    <label>Student Name: {{ $student->fname }} {{ $student->lname }}</label>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="light-card table-responsive p-b-3em">
                                <table class="table display companies-list" id="data-table">
                                    <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                        <th>Note</th>
                                        <th>Owed Balance</th>
                                        <th>Balance</th>
                                        <th>Receipt</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($balance_history))
                                    @foreach($balance_history as $each_balance_history)
                                    <tr>
                                        <td>{{ date('Y-m-d H:i',strtotime($each_balance_history->created_at)) }}</td>
                                        <td><strong>${{ $each_balance_history->amount }}</strong></td>
                                        <td>{{ $each_balance_history->action }}</td>
                                        <td>{{ $each_balance_history->note}}</td>
                                        <td>${{ $each_balance_history->owed_balance}}</td>
                                        <td>${{ $each_balance_history->balance}}</td>
                                        <td>
                                            @if($each_balance_history->invoice_id == 0)
                                            None
                                            @else
                                            <a href="{{url('PrintController@invoicePrint').$each_balance_history->invoice_id}}"  class="btn btn-success" style="padding: 1px 20px!important;">Receipt</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Add Fine dialog-->
    <div class="modal fade" id="add_fine" role="dialog" style="background-color: #fff;">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="holder-add-fine simcy-form" action="<?=url("Facebook@updateAddFine");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box"><div class="circle-loader"></div></div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- footer -->
{{ view("includes/footer"); }}

<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src="<?=url("");?>assets/js/jquery-ui.js"></script>


<script>
    $(document).ready(function() {
        $("#lease_start").datepicker({
            todayHighlight: !0,
            format: 'yyyy-mm-dd'
        });

        $( "#extend_stay_bt" ).click(function() {
            swal("First Payment!", "You have to make first payment for extending stay.", "error");
        });

        $('#view_id_dlg').dialog({
            width: 700,
            autoOpen: false,
            show: {
                effect: "blind",
                duration: 300
            },
            hide: {
                effect: "blind",
                duration: 300
            }
        });

        $( "#view_id_bt" ).click(function() {
            $('#view_id_dlg').dialog("open");
        });

        $(".custom-email-area").hide();
        $("[id='email_switch']").change(function(){
            if($(this).is(':checked')){
                $("[name='email_switch']").val("1");
                $(".custom-email-area").show();
                $(".email-template-area").hide();
            }
            else{
                $("[name='email_switch']").val("0");
                $(".custom-email-area").hide();
                $(".email-template-area").show();
            }
        });
    });

</script>

<script src="<?= url(""); ?>assets/js/room.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
</body>

</html>