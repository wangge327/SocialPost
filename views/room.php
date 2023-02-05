<?php include "includes/head.php" ?>
<body>
{{ view("includes/header", $data); }}
{{ view("includes/sidebar", $data); }}

<div class="content">
    <div class="page-title">
        <h3>Room and Bed Status</h3>
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
                        Bed Status:
                        <select id="status-select">
                            <option value="">All</option>
                            <option value="Vacant">Vacant</option>
                            <option value="Occupied">Occupied</option>
                            <option value="Unavailable">Unavailable</option>
                        </select>
                    </label>
                </div>
                <table class="table display companies-list" id="data-table" style="width: 92%;">
                    <thead>
                    <tr>
                        <th>Room Name</th>
                        <th>Bed Name</th>
<!--                        <th>Room Status</th>-->
                        <th>Bed Status</th>
                        <th>Student Name</th>
                        <th>Lease Start</th>
                        <th>Lease End</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ( count($rooms) > 0 )
                    @foreach ( $rooms as $index => $item )
                    <tr class="room-record">
                        <td><strong>{{ $item->name }}</strong></td>
                        <td></td>
<!--                        <td><strong>{{ $item->status }}</strong></td>-->
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @foreach ( $item->bed_data as $index => $bed_item )
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->name }}-{{ $bed_item->name }}</td>
<!--                        <td></td>-->
                        <td class="{{ $bed_item->status }}">{{ $bed_item->status }}</td>
                        <td>{{ $bed_item->student_name }}</td>
                        <td>{{ $bed_item->lease_start }}</td>
                        <td>{{ $bed_item->lease_end }}</td>
                        <td>
                            <a class="send-to-server-click"  data="bedid:{{ $bed_item->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Room@ChangeBedStatus");?>"  warning-button="Continue" loader="true" href="" warning-title="Are you sure?"
                                @if ($bed_item->status=="Vacant")
                                    warning-message="This Bed status will change to Unavailable.">Set Unavailable
                                @else
                                    warning-message="This Bed status changes to Vacant.">Set Vacant
                                @endif
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                    @else
                    <tr>
                        <td colspan="9" class="text-center">It's empty here</td>
                    </tr>
                    @endif
                    </tbody>
                </table>

                <div class="bed-statitic">
                    <div class="color-unavailable">
                        <span>Unavailable :</span>
                        <span>{{ $bed_statistic['unavailable_count'] }} ({{ $bed_statistic['unavailable_percent'] }}%)</span>
                    </div>
                    <div class="color-vacan">
                        <span>Vacant :</span>
                        <span>{{ $bed_statistic['vacant_count'] }} ({{ $bed_statistic['vacant_percent'] }}%)</span>
                    </div>
                    <div class="color-occupied">
                        <span>Occupied : </span>
                        <span>{{ $bed_statistic['occupied_count'] }} ({{ $bed_statistic['occupied_percent'] }}%)</span>
                    </div>
                    <div class="color-red">
                        <span>Total : {{ $bed_statistic['total_count'] }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- footer -->
{{ view("includes/footer"); }}

@if ( count($rooms) > 0 )
<script>
    function set_building(building_id){
        if(building_id == 0)
            location.replace("/room/list");
        else
            location.replace("/room/list?builing="+building_id);
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
        console.log(building_id);
        $(".builing-bt button").removeClass("bt-sel");
        if(building_id == "")
            $(".builing-bt .building-0").addClass("bt-sel");
        else
            $(".builing-bt .building-"+building_id).addClass("bt-sel");

        $('#data-table').DataTable({
            dom: 'Bfrtip',
            "bSort" : true,
            buttons: [
                'excelHtml5',
                'pdfHtml5'
            ]
        });

        $(".dataTables_filter").append($(".custom-filter"));
        $(".custom-filter").show();
        $('#per-page-select').on('change',function(){
            var selectedValue = $(this).val();
            $('#data-table').DataTable().page.len(selectedValue).draw();;
        });
        $('#status-select').on('change',function(){
            $('#data-table').DataTable().search(
                $('#status-select').val()
            ).draw();
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
