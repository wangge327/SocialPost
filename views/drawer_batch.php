<?php include "includes/head.php" ?>
<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
<!--            <div class="pull-right page-actions lower">-->
<!--                <button class="btn btn-primary" data-toggle="modal" data-target="#create_batch" data-backdrop="static" data-keyboard="false"><i class="ion-plus-round"></i> New Batch</button>-->
<!--            </div>-->
            <h3>Drawer Batch</h3>
            <p>All batch lists.</p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="light-card table-responsive p-b-5em">
                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th class="text-center w-70"></th>
<!--                                <th>User Name</th>-->
                                <th>Drawer Number</th>
                                <th>Open Amount</th>
                                <th>Closing Amount</th>
                                <th>Difference</th>
                                <th>Start time</th>
                                <th>End time</th>
                                <th>Status</th>
                                <th class="text-center">Add Closing Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($batchs) > 0 )
                            @foreach ( $batchs as $index => $batch )
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
<!--                                <td><strong>{{ $batch['user']->fname }} {{ $batch['user']->lname }}</strong> </td>-->
                                <td><a href="<?=url("Drawer@viewTransaction");?>{{ $batch['batch']->id }}" >
                                    <strong>{{ $batch['batch']->drawer_number }}</strong>
                                    </a>
                                </td>
                                <td><strong>{{ $batch['batch']->open_amount }}</strong> </td>
                                <td><strong>{{ $batch['batch']->closing_amount }}</strong> </td>
                                <td><strong>{{ $batch['batch']->difference }}</strong> </td>
                                <td><strong>{{ $batch['batch']->start_time }}</strong> </td>
                                <td><strong>{{ $batch['batch']->end_time }}</strong> </td>
                                <td><strong>{{ $batch['batch']->status }}</strong> </td>

                                <td class="text-center">
                                    <button class="fetch-display-click btn btn-primary" data="batchid:{{ $batch['batch']->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Drawer@closeview");?>" holder=".update-holder" modal="#close_batch" href="">Close batch</button>
<!--                                    <div class="dropdown">-->
<!--                                        <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>-->
<!--                                        <ul class="dropdown-menu" role="menu">-->
<!--                                            <li role="presentation">-->
<!--                                                <a class="fetch-display-click" data="batchid:{{ $batch['batch']->id }}|csrf-token:{{ csrf_token() }}" url="--><?//=url("Drawer@closeview");?><!--" holder=".update-holder" modal="#close_batch" href="">Close batch</a>-->
<!--                                                <a href="--><?//=url("Drawer@viewTransaction");?><!--{{ $batch['batch']->id }}" >Report</a>-->
<!--                                                @if($batch['batch']->closing_amount != '')-->
<!--                                                <a class="send-to-server-click"  data="batchid:{{ $batch['batch']->id }}|csrf-token:{{ csrf_token() }}" url="--><?//=url("Drawer@delete");?><!--" warning-title="Are you sure?" warning-message="This batch will be deleted." warning-button="Continue" loader="true" href="">Delete</a>-->
<!--                                                @endif-->
<!--                                            </li>-->
<!--                                        </ul>-->
<!--                                    </div>-->
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

    <!--Create Vatch-->
    <div class="modal fade" id="create_batch" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Batch</h4>
                </div>
                <form class="simcy-form" id="create-user-form" action="<?=url("Drawer@create");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Fill in batch's details</p>
                        <div class="form-group">
	                        <div class="row">
	                            <div class="col-md-12 ">
	                                <label>Open Amount</label>
	                                <input type="text" class="form-control" id="open_amount" placeholder="Open Amount" data-parsley-required="true" name="open_amount" value="300">
	                                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <div class="row">
	                            <div class="col-md-12">
	                                <label>Drawer Number</label>
	                                <input type="text" class="form-control" id="drawer_number" placeholder="Drawer Number" data-parsley-required="true" name="drawer_number">
	                            </div>
	                        </div>
	                    </div>
	                    
                    </div>
                    <div class="modal-footer">                    	
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Batch</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <!-- Close batch Modal -->
    <div class="modal fade" id="close_batch" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Close Batch</h4>
                </div>
                <form class="update-holder simcy-form" id="close-batch-form" action="<?=url("Drawer@close");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box"><div class="circle-loader"></div></div>
                </form>
            </div>

        </div>
    </div>
	
	    
    <!-- View Transaction Modal -->
    <div class="modal fade" id="view_transaction" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">View Transaction</h4>
                </div>
                <form class="update-holder simcy-form" id="view-transaction-form" action="" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box"><div class="circle-loader"></div></div>
                </form>
            </div>

        </div>
    </div>
    
    <!-- footer -->
    {{ view("includes/footer"); }}

    @if ( count($batchs) > 0 )
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
