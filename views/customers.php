<?php include "includes/head.php" ?>
<script src="//code.jquery.com/jquery-1.12.4.js"></script>

<body>

    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}

    <div class="content">
        <div class="page-title">
            <div class="row student">
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create" data-backdrop="static" data-keyboard="false"><i class="ion-plus-round"></i>Add Member
                    </button>
                </div>
                <div class="col-md-3 hidden">
                    <form class="simcy-form page-actions lower" method="post" loader="true" action="<?= url("Member@Excel"); ?>" enctype="multipart/form-data" style="margin:20px;">
                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">

                        <label>Student Excel File</label> <input type="file" name="file" id="file" accept=".xlsx">
                        <button type="submit" id="submit" name="action" value="import" class="btn btn-success"><i class="ion-email-unread"></i>Import
                        </button>
                    </form>
                </div>
                <div class="col-md-3 hidden">
                    <form class="simcy-form page-actions lower" method="POST" loader="true" action="<?= url("Member@importWithUnit"); ?>" enctype="multipart/form-data" style="margin:20px;">
                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">

                        <label>Student Excel File With Unit</label>
                        <input type="file" name="file" id="file" accept=".xlsx">
                        <button type="submit" id="submit" name="action" value="import_unit" class="btn btn-success"><i class="ion-email-unread"></i>Import
                        </button>
                    </form>
                </div>
                <div class="col-md-3">
                    <form method="post" action="<?= url("Member@Excel"); ?>">
                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
                        <button class="btn btn-success" name="action" value="export"><i class="ion-code-download"></i>
                            Export to Excel File
                        </button>
                    </form>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-success" data-toggle="modal" data-target="#send-email" data-backdrop="static" data-keyboard="false">Send Email to all students</button>
                </div>
            </div>
            <p>List of Members.</p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em">
                    <div class="custom-filter" style="display: none">
                        <label>
                            Display Per Page:
                            <select id="per-page-select">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                            </select>
                        </label>
                        <label style="margin-left: 20px">
                            Sign:
                            <select id="action-select">
                                <option value="">All</option>
                                <option value='Pending'>Pending</option>
                                <option value='Sent'>Sent</option>
                                <option value='Signed'>Signed</option>
                                <option value='Lease on File'>Lease on File</option>
                            </select>
                        </label>
                        <label style="margin-left: 20px">
                            Status:
                            <select id="status-select">
                                <option value="">All</option>
                                <option value='Created'>Created</option>
                                <option value='Arrived'>Arrived</option>
                                <option value='Extended'>Extended</option>
                                <option value='Terminated'>Terminated</option>
                            </select>
                        </label>
                    </div>

                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th class="text-center w-70">Photo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th class="text-center">Sign</th>
                                <th class="text-center">Status</th>
                                <th class="text-center w-50">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($customers) > 0 )
                            @foreach ( $customers as $index => $customer )
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="text-center">
                                    @if ( !empty($customer['user']->avatar) )
                                    <img src="<?= url("/") ?>uploads/avatar/{{ $customer['user']->avatar }}" class="img-responsive img-circle table-avatar">
                                    @else
                                    <img src="<?= url("/") ?>assets/images/avatar.png" class="img-responsive table-avatar">
                                    @endif
                                </td>
                                <td>
                                    <a href="<?= url("Customer@profile"); ?>{{$customer['user']->id}}">
                                        <strong>{{ $customer['user']->fname }} {{ $customer['user']->lname }}</strong>
                                    </a>
                                </td>
                                <td><strong>{{ $customer['user']->email }}</strong></td>
                                <td class="text-center">
                                    <span class="label
                                    @if ( $customer['user']->sign_status == 'Pending' )
                                        label-warning
                                    @elseif ( $customer['user']->sign_status == 'Sent' )
                                        label-info
                                    @else
                                        label-success
                                    @endif
                                    ">
                                        {{ $customer['user']->sign_status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="label
                                    @if ( $customer['user']->status == 'Created' )
                                        label-warning
                                    @elseif ( $customer['user']->status == 'Arrived' )
                                        label-success
                                    @elseif ( $customer['user']->status == 'Extended' )
                                        label-info
                                    @else
                                        label-danger
                                    @endif
                                    ">
                                        {{ $customer['user']->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>
                                        <ul class="dropdown-menu" role="menu">
                                            <li role="presentation">
                                                <a class="fetch-display-click" data="customerid:{{ $customer['user']->id }}|table:users|csrf-token:{{ csrf_token() }}" url="<?= url("Customer@updateview"); ?>" holder=".update-holder" modal="#update" href="">Edit</a>
                                                <a class="send-to-server-click" data="customerid:{{ $customer['user']->id }}|csrf-token:{{ csrf_token() }}" url="<?= url("Customer@delete"); ?>" warning-title="Are you sure?" warning-message="This Member's data will be deleted." warning-button="Continue" loader="true" href="">Delete</a>

                                            </li>

                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="9" class="text-center">It's empty here</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!--Create User Account-->
    <div class="modal fade" id="create" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Student</h4>
                </div>
                <form class="simcy-form" id="create-customer-form" action="<?= url("Customer@create"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Fill in Member's details, a signed email will be sent to the student.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 ">
                                    <label>First name </label> <label class="color-red">*</label>
                                    <input type="text" class="form-control" name="fname" placeholder="First name" data-parsley-required="true">
                                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                                </div>
                                <div class="col-md-6">
                                    <label>Last name</label> <label class="color-red">*</label>
                                    <input type="text" class="form-control" name="lname" placeholder="Last name" data-parsley-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Email address</label> <label class="color-red">*</label>
                                    <input type="email" class="form-control" name="email" placeholder="Email address" data-parsley-required="true">
                                </div>
                                <div class="col-md-6">
                                    <label>Phone number</label><br>
                                    <select class="form-control phone_country" name="phone_country">
                                        <option value="US">US +1</option>
                                        @foreach($phone_code as $each_phone_code)
                                        <option value="{{$each_phone_code->iso}}">
                                            {{$each_phone_code->iso}} +{{$each_phone_code->phonecode}}
                                        </option>
                                        @endphp
                                    </select>
                                    <input type="number" class="form-control" name="phone" placeholder="Phone number" style="width: 64%">
                                </div>
                            </div>
                        </div>
                        <?php include 'extras/student_details.php' ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Sign Up</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Update User Modal -->
    <div class="modal fade" id="update" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Student Data</h4>
                </div>
                <form class="update-holder simcy-form" id="update-customer-form" action="<?= url("Customer@update"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box">
                        <div class="circle-loader"></div>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!--Room Assign dialog-->
    <div class="modal fade" id="room" role="dialog" style="background-color: #fff;">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Room Assignment</h4>
                </div>
                <form class="update-holder-room simcy-form" action="<?= url("Customer@updateRoom"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box">
                        <div class="circle-loader"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--Cancel Lease dialog-->
    <?php include "customer/modal_cancel_lease.php" ?>

    <!-- Send Email -->
    <div class="modal fade" id="send-email" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Send Email to all Students</h4>
                </div>
                <form class="simcy-form" id="edit-customer-form" action="<?= url("Customer@sendAllEmail"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />

                    <div class="modal-body">
                        <p>Enter Mail content.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Title</label>
                                    <input type="text" class="form-control" name="mail_title" placeholder="Email title" data-parsley-required="true">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Content</label>
                                    <textarea class="form-control" name="mail_content" data-parsley-required="true" rows="5"></textarea>
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


    <!-- footer -->
    {{ view("includes/footer"); }}

    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="<?= url("/"); ?>assets/js/jquery-ui.js"></script>

    @if ( count($customers) > 0 )
    <script>
        $(document).ready(function() {

            $('#data-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'pdfHtml5',
                ]
            });

            $(".dataTables_filter").append($(".custom-filter"));
            $(".custom-filter").show();

            $('#status-select').on('change', function() {
                $('#data-table').DataTable().search(
                    $('#status-select').val()
                ).draw();
            });

            $('#action-select').on('change', function() {
                $('#data-table').DataTable().search(
                    $('#action-select').val()
                ).draw();
            });

            $('#per-page-select').on('change', function() {
                var selectedValue = $(this).val();
                $('#data-table').DataTable().page.len(selectedValue).draw();;
            });

            $("[name='room_pay']").change(function() {
                if ($(this).is(':checked'))
                    $(this).val(1);
                else
                    $(this).val(0);
            });
            $("[name='administration_pay']").change(function() {
                if ($(this).is(':checked'))
                    $(this).val(1);
                else
                    $(this).val(0);
            });
            $("[name='security_pay']").change(function() {
                if ($(this).is(':checked'))
                    $(this).val(1);
                else
                    $(this).val(0);
            });
            $("[name='laundry_pay']").change(function() {
                if ($(this).is(':checked'))
                    $(this).val(1);
                else
                    $(this).val(0);
            });

            $("[id='print_lease']").change(function() {
                if ($(this).is(':checked')) {
                    var student_lease_email = "student{{count($customers)}}@irhliving.com";
                    $("[name='print_lease']").val(student_lease_email);
                    $("[name='email']").val("");
                    $("[name='email']").prop("disabled", true);
                    $("[name='email']").attr("data-parsley-required", "false");
                    swal("Print Lease!", "Your email is " + student_lease_email, "success");
                } else {
                    $("[name='email']").prop("disabled", false);
                    $("[name='email']").attr("data-parsley-required", "true");
                }
            });

            $("[id='weekly_rate_check']").change(function() {
                if ($(this).is(':checked')) {
                    $("[id='weekly_rate']").show();
                } else {
                    $("[id='weekly_rate']").hide();
                }
            });

            $('.select-employer').change(function() {
                if ($(this).val() == 'other') {
                    $('#create_employer').collapse('show');
                }
            });

            $("#save_employer").click(function() {
                $.ajax({
                    url: baseUrl + 'students/create_employer',
                    type: "post",
                    data: {
                        name: $("#employer_name").val(),
                        company_info: $("#company_info").val(),
                        email: $("#employer_email").val(),
                        room_pay: $("[name='room_pay']").val(),
                        administration_pay: $("[name='administration_pay']").val(),
                        security_pay: $("[name='security_pay']").val(),
                        laundry_pay: $("[name='laundry_pay']").val(),
                        "csrf-token": Cookies.get("CSRF-TOKEN")
                    },
                    success: function(data) {
                        swal("Employer!", "New Employer Created", "success");
                        $(".select-employer").find('option').remove();
                        $(".select-employer").append(data);
                        $('#create_employer').collapse('hide');
                    }
                });
            });

            $(".compare_sec_input").keyup(function() {
                var company_security_deposit = {
                    {
                        $company_security
                    }
                };
                if (company_security_deposit == $(".compare_sec_input").val())
                    $(".compare_sec_label").hide();
                else
                    $(".compare_sec_label").show();
            });

            $(".compare_weekly_input").keyup(function() {
                var company_weely_rate = {
                    {
                        $company_weekly
                    }
                };
                if (company_weely_rate == $(".compare_weekly_input").val())
                    $(".compare_weekly_label").hide();
                else
                    $(".compare_weekly_label").show();
            });

        });
        let baseUrl = '<?= url("/"); ?>';
        let csrf = '<?= csrf_token(); ?>';
    </script>
    @endif

    <script src="<?= url("/"); ?>assets/js/room.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
</body>

</html>