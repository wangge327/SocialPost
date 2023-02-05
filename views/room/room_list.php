{{ view("includes/head", $data); }}
<body>
{{ view("includes/header", $data); }}
{{ view("includes/sidebar", $data); }}

<div class="content">
    <div class="page-title">
        <h3>Room and Bed List</h3>
        <button class="btn btn-primary" data-toggle="modal" data-target="#create_room" data-backdrop="static" data-keyboard="false"><i class="ion-plus-round"></i>Create Room</button>
    </div>

    <div class="row">

        <div class="col-md-12">
            <div class="light-card table-responsive p-b-3em room-page">
                <div class="builing-bt">
                    @if ( count($buildings) > 0 )
                    @foreach ( $buildings as $index => $item )
                    <button class="building-{{ $item->id }}" onclick="set_building({{ $item->id }})">
                        {{ $item->name }}
                    </button>
                    @endforeach
                    @endif
                    <button class="building-0" onclick="set_building(0)">Show All</button>
                </div>

                <table class="table display companies-list" id="data-table" style="width: 92%;">
                    <thead>
                    <tr>
                        <th>Room Name</th>
                        <th>Bed Count</th>
                        <th>Bed Name</th>
                        <th style="width: 350px;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ( count($rooms) > 0 )
                    @foreach ( $rooms as $index => $item )
                    <tr class="room-record">
                        <td><strong>{{ $item->name }}</strong></td>
                        <td>{{count($item->bed_data)}}</td>
                        <td>
                            @foreach ( $item->bed_data as $index => $bed_item )
                            {{ $bed_item->name }},
                            @endforeach
                        </td>
                        <td>
                            <a class="btn btn-primary" href="<?=url("Room@reviewRoom");?>{{$item->id}}">Open Door</a>
                            <a class="btn btn-primary" href="<?=url("Room@reviewRoom");?>{{$item->id}}">Assign Bed</a>
                            <a class="btn btn-success" data="roomid:{{ $item->id }}|table:rooms|csrf-token:{{ csrf_token() }}" url="<?=url("Room@delete");?>" warning-title="Are you sure?" warning-message="This Room will be deleted." warning-button="Continue" loader="true" >Delete</a>
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

<!--Create Room-->
<div class="modal fade" id="create_room" role="dialog">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Room</h4>
            </div>
            <form class="simcy-form" id="create-user-form" action="<?=url("Room@create");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p>Please select building to create room</p>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 ">
                                <label>Select Building</label>
                                <select class="form-control" name="building_id">
                                    @foreach($buildings as $building)
                                    <option value="{{$building->id}}">{{$building->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 ">
                                <label>Room name</label>
                                <input type="text" class="form-control" id="room_name" placeholder="Room name" data-parsley-required="true" name="name">
                                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Room</button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- footer -->
{{ view("includes/footer"); }}

@if ( count($rooms) > 0 )
<script>
    function set_building(building_id){
        if(building_id == 0)
            location.replace("/room/room_list");
        else
            location.replace("/room/room_list?builing="+building_id);
    }

    function getParam(sname) {
        var params = location.search.substr(location.search.indexOf("?") + 1);
        var sval = "";
        params = params.split("&");
        for (var i = 0; i < params.length; i++) {
            temp = params[i].split("=");
            if ([temp[0]] == sname) { sval = temp[1]; }
        }
        return sval;
    }

    $(document).ready(function() {
        var building_id = getParam("builing");

        $(".builing-bt button").removeClass("bt-sel");
        if(building_id == "")
            $(".builing-bt .building-0").addClass("bt-sel");
        else
            $(".builing-bt .building-"+building_id).addClass("bt-sel");

        $('#data-table').DataTable({
            dom: 'Bfrtip',
            "bSort" : true,
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5'
            ]
        });


    });
    let baseUrl = '<?=url("");?>';
    let csrf='<?=csrf_token();?>';
</script>
@endif
<script src="<?= url(""); ?>assets/js/room.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
</body>

</html>
