<?php include "includes/head.php" ?>
<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
            <div class="pull-right page-actions lower">
                <button class="btn btn-primary" data-toggle="modal" data-target="#create_employer" data-backdrop="static" data-keyboard="false"><i class="ion-plus-round"></i> New Employer</button>
            </div>
            <h3>Employers</h3>
            <p>The employer would pay the same fee for all students that he is assigned too.</p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em">
                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th class="text-center w-70"></th>
                                <th>Icon</th>
                                <th>Name</th>
                                <th>Company Info</th>
                                <th>Payment Options</th>
                                <th class="text-center w-70">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($employers) > 0 )
                            @foreach ( $employers as $index => $employer )
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">
                                    <div class="{{ $employer['color'] }} campany-icon">{{ mb_substr($employer['employer']->name, 0, 1, 'utf-8') }}</div>
                                </td>
                                <td><strong>{{ $employer['employer']->name }}</strong><br>{{ $employer['employer']->email }}</td>
                                <td><strong>{{ $employer['employer']->company_info }} </td>
                                <td><strong>{{ $employer['payment_items'] }} </td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>
                                        <ul class="dropdown-menu" role="menu">
                                            <li role="presentation">
                                                <a class="fetch-display-click" data="employerid:{{ $employer['employer']->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Employer@updateview");?>" holder=".update-holder" modal="#update" href="">Edit</a>
                                                <a class="send-to-server-click"  data="employerid:{{ $employer['employer']->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Employer@delete");?>" warning-title="Are you sure?" warning-message="This employer will be deleted." warning-button="Continue" loader="true" href="">Delete</a>
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

    <!--Create Employer Account-->
    <div class="modal fade" id="create_employer" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Employer</h4>
                </div>
                <form class="simcy-form" id="create-user-form" action="<?=url("Employer@create");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Fill in employer's details, The employer would pay the same fee for all students that he is assigned too.</p>
                        <div class="form-group">
	                        <div class="row">
	                            <div class="col-md-12 ">
	                                <label>Employer name</label>
	                                <input type="text" class="form-control" id="employer_name" placeholder="Employer name" data-parsley-required="true" name="name">
	                                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <div class="row">
	                            <div class="col-md-12">
	                                <label>Company Information</label>
	                                <input type="text" class="form-control" id="company_info" placeholder="Company Information" data-parsley-required="true" name="company_info">
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <div class="row">
	                            <div class="col-md-12">
	                                <label>Employer Email</label>
	                                <input type="email" class="form-control" id="employer_email" placeholder="Employer Email" data-parsley-required="true" name="email">
	                            </div>
	                        </div>
	                    </div>
	                    <p>Payment Option</p>
	                    <div class="form-group">
	                        <div class="row">
	                            <div class="col-md-12">
	                                <label style="width: 130px;">Room fee : </label>
	                                <input type="checkbox" class="switch" id="room_pay"/>
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-md-12">
	                                <label style="width: 130px;">Administration fee : </label>
	                                <input type="checkbox" class="switch" id="administration_pay"/>
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-md-12">
	                                <label style="width: 130px;">Security fee : </label>
	                                <input type="checkbox" class="switch" id="security_pay"/>
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-md-12">
	                                <label style="width: 130px;">Laundry fee : </label>
	                                <input type="checkbox" class="switch" id="laundry_pay"/>
	                            </div>
	                        </div>
	                    </div>
                        
                        <!--<div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Profile picture</label>
                                    <input type="file" name="avatar" class="croppie" default="<?=url("")?>assets/images/avatar.png" crop-width="200" crop-height="199"  accept="image/*">
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <div class="modal-footer">
                    	<input type="hidden" name="room_pay" value="0">
                    	<input type="hidden" name="administration_pay" value="0">
                    	<input type="hidden" name="security_pay" value="0">
                    	<input type="hidden" name="laundry_pay" value="0">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Employer</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <!-- Update Employer Modal -->
    <div class="modal fade" id="update" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Employer Account </h4>
                </div>
                <form class="update-holder simcy-form" id="update-company-form" action="<?=url("Employer@update");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box"><div class="circle-loader"></div></div>
                </form>
            </div>

        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

    @if ( count($employers) > 0 )
    <script>
        $(document).ready(function() {
            $('#data-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5'
                ]
            });
            
            $("[id='room_pay']").change(function(){				
	            if($(this).is(':checked'))
	                $("[name='room_pay']").val(1);
	            else
	                $("[name='room_pay']").val(0);
	        });
	        $("[id='administration_pay']").change(function(){				
	            if($(this).is(':checked'))
	                $("[name='administration_pay']").val(1);
	            else
	                $("[name='administration_pay']").val(0);
	        });
	        $("[id='security_pay']").change(function(){				
	            if($(this).is(':checked'))
	                $("[name='security_pay']").val(1);
	            else
	                $("[name='security_pay']").val(0);
	        });
	        $("[id='laundry_pay']").change(function(){				
	            if($(this).is(':checked'))
	                $("[name='laundry_pay']").val(1);
	            else
	                $("[name='laundry_pay']").val(0);
	        });
        });
    </script>
    @endif
</body>

</html>
