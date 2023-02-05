{{ view("includes/head", $data); }}
<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
            <div class="pull-right page-actions lower">
                @if ( count($beds) > 3 )
                <button class="btn btn-primary" onclick='swal("Bed exceeded!", "You can assign beds maximum 4.", "error")'><i class="ion-plus-round"></i>Create Bed</button>
                @else
                <button class="btn btn-primary" data-toggle="modal" data-target="#create_bed" data-backdrop="static" data-keyboard="false"><i class="ion-plus-round"></i>Create Bed</button>
                @endif
            </div>
            <h3>Room Information</h3>
            <p>Building: {{$building->name}}</p>
            <p>Room: {{$room->name}}</p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em">
                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th class="text-center w-70"></th>
                                <th>Bed Name</th>
                                <th>Bed Status</th>
                                <th>Student Name</th>
                                <th class="text-center w-70">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($beds) > 0 )
                            @foreach ( $beds as $index => $bed )
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $bed->name }}</strong><br>{{ $employer['employer']->email }}</td>
                                <td><strong>{{ $bed->status }} </td>
                                <td><strong>{{ $bed->student_name }} </td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>
                                        <ul class="dropdown-menu" role="menu">
                                            <li role="presentation">
                                                <a class="fetch-display-click" data="bedid:{{ $bed->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Room@updateViewBed");?>" holder=".update-holder" modal="#update-bed" href="">Edit</a>
                                                <a class="send-to-server-click"  data="bedid:{{ $bed->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Room@deleteBed");?>" warning-title="Are you sure?" warning-message="This Bed will be deleted." warning-button="Continue" loader="true" href="">Delete</a>
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

    <!--Create Bed-->
    <div class="modal fade" id="create_bed" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Bed</h4>
                </div>
                <form class="simcy-form" id="create-user-form" action="<?=url("Room@createBed");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Please input bed name</p>
                        <div class="form-group">
	                        <div class="row">
	                            <div class="col-md-12 ">
	                                <label>Bed name</label>
	                                <input type="text" class="form-control" id="bed_name" placeholder="Bed name" data-parsley-required="true" name="name">
	                                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
	                                <input type="hidden" name="room_id" value="{{ $room->id }}" />
	                            </div>
	                        </div>
	                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Bed</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <!-- Update Bed -->
    <div class="modal fade" id="update-bed" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Bed</h4>
                </div>
                <form class="update-holder simcy-form" id="update-bed-form" action="<?=url("Room@updateBed");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
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
        });
    </script>
    @endif
</body>

</html>
