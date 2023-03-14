<?php include "includes/head.php" ?>

<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
            <div class="pull-right page-actions lower">
                <button class="btn btn-primary" data-toggle="modal" data-target="#special-room-fee" data-backdrop="static" data-keyboard="false"><i class="ion-plus-round"></i>Add Special fee</button>
            </div>
            <h3>Special Room Fees</h3>
            <p>All Special Room Fees.</p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em">
                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th class="text-center w-70"></th>
                                <th>Building Name</th>
                                <th>Room Name</th>
                                <th>Bed Name</th>
                                <th>Weekly Fee</th>
                                <th>Daily Fee</th>
                                <th>Created</th>
                                <th class="text-center w-70">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($special_room) > 0 )
                            @foreach ( $special_room as $index => $each_special_room )
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{$each_special_room->building_name}}</td>
                                <td>{{$each_special_room->room_name}}</td>
                                <td>{{$each_special_room->bed_name}}</td>
                                <td>{{$each_special_room->weekly}}</td>
                                <td>{{$each_special_room->daily}}</td>
                                <td>{{$each_special_room->created_at}}</td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>
                                        <ul class="dropdown-menu" role="menu">
                                            <li role="presentation">
                                                <a class="fetch-display-click" data="special_room_id:{{ $each_special_room->id }}|csrf-token:{{ csrf_token() }}" url="<?= url("FeeManage@updateSpecialFeeView"); ?>" holder=".update-holder" modal="#update-room-fee" href="">Edit</a>
                                                <a class="send-to-server-click" data="special_room_id:{{ $each_special_room->id }}|csrf-token:{{ csrf_token() }}" url="<?= url("FeeManage@deleteSpecialFee"); ?>" warning-title="Are you sure?" warning-message="This Special fee will be default." warning-button="Continue" loader="true" href="">Return to Default</a>
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

    <!-- Add Room Fee dialog-->
    <div class="modal fade" id="special-room-fee" role="dialog" style="background-color: #fff;">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Special Room Fee</h4>
                </div>
                <form class="update-holder-room simcy-form" action="<?= url("FeeManage@createSpecialFee"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Please select room and bed and input special fee.</p>
                        <hr>
                        <?php include "extras/select_room.php" ?>

                        <hr>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Room Weekly Fee($)</label>
                                    <input type="number" class="form-control" name="weekly" placeholder="weekly fee" required id="weekly_input">
                                </div>
                                <div class="col-md-6">
                                    <label>Room Daily Fee($)</label>
                                    <input type="text" class="form-control" placeholder="daily fee" disabled id="daily_input">
                                    <input type="hidden" class="form-control" name="daily" placeholder="daily fee" id="daily_input_hidden">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="csrf-token" value="{{ csrf_token(); }}" />

                        <hr>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Fee</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <!-- Update Special Modal -->
    <div class="modal fade" id="update-room-fee" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Special Room Fee</h4>
                </div>
                <form class="update-holder simcy-form" id="update-special-room-form" action="<?= url("FeeManage@updateSpecialFee"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box">
                        <div class="circle-loader"></div>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

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

        let baseUrl = '<?= url("/"); ?>';
        let csrf = '<?= csrf_token(); ?>';

        $("#weekly_input").keyup(function() {
            var daily_fee = Math.round($(this).val() / 7 * 100) / 100;
            $("#daily_input").val(daily_fee);
            $("#daily_input_hidden").val(daily_fee);
        });
    </script>
    <!-- scripts -->

    <script src="<?= url("/"); ?>assets/js/room.js"></script>

    <style>
        .dt-buttons {
            display: none;
        }
    </style>
</body>

</html>