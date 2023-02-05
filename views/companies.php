<?php include "includes/head.php" ?>
<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
            <div class="pull-right page-actions lower">
                <button class="btn btn-primary" data-toggle="modal" data-target="#create_company" data-backdrop="static" data-keyboard="false"><i class="ion-plus-round"></i> New Sponsor</button>
            </div>
            <h3>Sponsors</h3>
            <p>The Sponsor would be company for Saas.</p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em">
                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th class="text-center w-70"></th>
                                <th>Name</th>
                                <th>Information</th>
                                <th>Address</th>
                                <th>Slug</th>
                                <th class="text-center w-70">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($companies) > 0 )
                            @foreach ( $companies as $index => $company )
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $company->name }}</strong><br></td>
                                <td><strong>{{ $company->information }}</strong><br></td>
                                <td><strong>{{ $company->address }}</strong><br></td>
                                <td><strong>{{ $company->slug }} </strong></td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>
                                        <ul class="dropdown-menu" role="menu">
                                            <li role="presentation">
                                                <a class="fetch-display-click" data="companyid:{{ $company->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Company@updateview");?>" holder=".update-holder" modal="#update" href="">Edit</a>
                                                <a class="send-to-server-click"  data="companyid:{{ $company->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Company@delete");?>" warning-title="Are you sure?" warning-message="This Sponsor will be deleted." warning-button="Continue" loader="true" href="">Delete</a>
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

    <!--Create Company Account-->
    <div class="modal fade" id="create_company" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Sponsor</h4>
                </div>
                <form class="simcy-form" id="create-user-form" action="<?=url("Company@create");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Fill in Sponsor's details.</p>
                        <div class="form-group">
	                        <div class="row">
	                            <div class="col-md-12 ">
	                                <label>Sponsor name</label>
	                                <input type="text" class="form-control" id="company_name" placeholder="Sponsor name" data-parsley-required="true" name="name">
	                                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
	                            </div>
	                        </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-12 ">
                                    <label>Sponsor Information</label>
                                    <input type="text" class="form-control" placeholder="Sponsor Information" data-parsley-required="true" name="information">
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-12 ">
                                    <label>Address</label>
                                    <input type="text" class="form-control" placeholder="Address" data-parsley-required="true" name="address">
                                </div>
                            </div>
	                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Sponsor</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <!-- Update Company Modal -->
    <div class="modal fade" id="update" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Sponsor</h4>
                </div>
                <form class="update-holder simcy-form" id="update-company-form" action="<?=url("Company@update");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box"><div class="circle-loader"></div></div>
                </form>
            </div>

        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

    @if ( count($companies) > 0 )
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
        });
    </script>
    @endif
</body>

</html>
