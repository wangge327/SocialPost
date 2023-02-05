{{ view("includes/head", $data); }}
<style>
.content{
    margin: 0 auto;
}
/*do not remove this*/
.tooltip.right {
    padding: 0 5px;
    margin-left: -5px;
}

.tooltip-inner {
    max-width: 200px;
    padding: 3px 8px;
    color: #fff;
    text-align: left;
    background-color: #000;
    border-radius: 4px;
}
</style>

<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}

    <div class="content room-bed-status">
        <div class="row">
            <div class="room-page" style="display: inline;">
                <div class="bed-statitic" style="float: left;margin: 0;border: none;">
                    <div style="margin-left: 40px; color: #5F220E;">
                        <span>Unavailable :</span>
                        <span id="span-unavailable"></span>
                    </div>
                    <div class="color-vacan" style="margin-left: 40px;">
                        <span>Vacant :</span>
                        <span id="span-vacant"></span>
                    </div>
                    <div class="color-occupied" style="margin-left: 40px;">
                        <span>Occupied : </span>
                        <span id="span-occupied"></span>
                    </div>
                    <div class="color-red" style="margin-left: 40px;">
                        <span>Total : </span>
                        <span id="span-total"></span>
                    </div>
                </div>
                <div class="navbar_section">
                    <ul class="nav nav-pills building-tab-area">
                    </ul>
                </div>
            </div>

            <div class="col-md-12" style="background: #fff;margin-top: 20px;">
                <hr>
                <aside class="col-md-3 d-flex" style="margin-bottom: 20px;">
                    <div class="d-flex flex-column w-100">
                        <div class="card">
                            <?php include "donut_chart.php" ?>
                        </div>
                        <div class="card mt-3 flex-grow-1">
                            <div class="card-body overflow-auto ">
                                <div class="cards beds-table">
                                    <table class="table table_scroll ">
                                        <thead>
                                            <tr>
                                                <th>Bed <span class=""></span></th>
                                                <th>Status <span class=""></span></th>
                                                <th>Name <span class=""></span></th>
                                                <th>Gender <span class=""></span></th>
                                            </tr>
                                        </thead>
                                        <tbody class="side-room-content">
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <span class="total-beds"></span>
                            </div>
                        </div>
                    </div>
                </aside>
                <main class="col-md-9">
                    <div class="main-content">
                        <table class="table table-borderless react-draggable react-draggable-dragged" data-table="Building table" style="transform: translate(0px, 0px);">
                            <tbody>
                            <tr class="floor-content-area">
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-8">
                        <span class="show_vacant">&nbsp; </span> &nbsp; Vacant
                        <span class="show_occupied">&nbsp; </span> &nbsp; Occupied
                        <span class="show_available"> </span> &nbsp; Unavailable
                        <span class="show_lockout">&nbsp; </span> &nbsp; Lock Out
                    </div>
                </main>
            </div>
        </div>
    </div>

    <div id="popup-root" class="hidden">
        <div class="popup-content">
            <table class="tooltol">
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

    <script src='https://cdnjs.cloudflare.com/ajax/libs/snap.svg/0.3.0/snap.svg-min.js'></script>
    <script src="<?=url("");?>assets/js/svg-donut-chart-framework.js"></script>
    <script>
        $(document).ready(function() {
            reload_data();
        });
        let baseUrl = '<?=url("");?>';
        let csrf='<?=csrf_token();?>';
        let building_id='<?=input('building');?>';

        add_building_tab();
        
        function reload_data(){
            $.ajax({
                url: "/room/block-list?building_id="+building_id,
                type: "get",
                data: {
                },
                success: function(data){
                    console.log(data)
                    drawChart(data.status);
                    add_floor_content(data);
                    add_side_beds(data);
                    add_tooltips(data);
                }
            });
		}

		function add_building_tab(){
            const building_count=6;
            var temp_cont = '';
            for(i = 1 ; i <= building_count; i++ ){
                if( i == building_id)
                    temp_cont += '<li class="nav-item"> <a class="nav-link nav_button active" href="?building=' + i + '" aria-current="page">   Building '+i+'</a> </li>';
                else
                    temp_cont += '<li class="nav-item"> <a class="nav-link nav_button" href="?building=' + i + '" aria-current="page">   Building '+i+'</a> </li>';
            }
            $( ".building-tab-area" ).html( temp_cont );
        }
        function add_floor_content(content_data){
            var floor_data = content_data['floors'];
            var floor_str = "";

            for(i = 0 ; i < floor_data.length; i++ ) {
                var each_floor = floor_data[i];
                var room_str = "";
                var left_right_position = true;

                floor_str += '<td><table class="table table-borderless"><tbody><tr><td><h2>' + content_data['floors_prefix'][i] + ' Floor</h2> </td> </tr><tr> <td> <table class="table floor_table" data-table="Floor table"> <tbody> <tr> <td class="room-type-td-others" colspan="2">STAIR</td> </tr>';

                for(j = 0 ; j < each_floor.length ; j++){
                    var each_room = each_floor[j]['beds'];
                    var bed_str = '';
                    console.log(each_room);

                    for(k = 0 ; k < each_room.length ; k++){
                        bed_str+= '<td class="p-1 bed-' + each_room[k]['id'] + '" data-toggle="tooltip"> <span class="color-white ';
                        if(each_room[k]['status'] == "Vacant")
                            bed_str += 'box_vacant';
                        else if(each_room[k]['status'] == "Occupied"){
                            if (each_room[k]['user']['intern'])
                                bed_str += 'box_intern';
                            else
                                bed_str += 'box_occupied';
                        }
                        else
                            bed_str += 'box_unavailable';

                        bed_str+= '" aria-describedby="popup-1">' + each_room[k]['bedName'] + '</span> </td>';

                    }
                    if(left_right_position){
                        room_str += '<tr><td class="room-type-td-room"><table class="table mb-0 bed_table" data-table="Beds Table"><tbody> <tr>';
                        room_str += ' <td class="w_room p-1">' + each_floor[j]["roomNo"] + '</td>';
                        room_str += bed_str;
                        room_str += ' </tr> </tbody> </table></td>';
                    }
                    else{
                        room_str += '<td class="room-type-td-room"><table class="table mb-0 bed_table" data-table="Beds Table"><tbody> <tr>';
                        room_str += bed_str;
                        room_str += ' <td class="w_room p-1">' + each_floor[j]["roomNo"] + '</td>';
                        room_str += ' </tr> </tbody> </table></td></tr>';
                    }

                    left_right_position ? (left_right_position=false) : (left_right_position=true);
                }

                floor_str += room_str;

                floor_str += '</tr> <tr> <td class="room-type-td-others" colspan="2">STAIR</td> </tr> </tbody> </table> </td> <td width="20"></td> </tr></tbody> </table> </td>';
                $(".floor-content-area").html(floor_str);
            }

        }

        function add_side_beds(content_data){
            var floor_data = content_data['floors'];
            var bed_str = '';
            for(i = 0 ; i < floor_data.length; i++ ) {
                var each_floor = floor_data[i];
                for(j = 0 ; j < each_floor.length ; j++){
                    var each_room = each_floor[j]['beds'];
                    for(k = 0 ; k < each_room.length ; k++){
                        bed_str += '<tr class="bt_grey"> <td class="active_bed">' + each_floor[j]["roomNo"] + '-' + each_room[k]['bedName'] + '</td> <td class="active_bed text-uppercase">' + each_room[k]['status'] + '</td> <td class="active_bed">';
                        if(each_room[k]["user"] != null)
                            bed_str += each_room[k]["user"]["name"] + '</td> <td class="active_bed">Male</td> </tr>';
                        else
                            bed_str += '</td> <td class="active_bed"></td> </tr>';
                    }
                }
                $(".side-room-content").html(bed_str);
            }

            $(".total-beds").html("Total Beds : " + content_data['total_beds']);
            $("#span-total").html( content_data['all_total_beds']);
            $("#span-occupied").html( content_data['all_status'][0]['count'] + '(' + content_data['all_status'][0]['percent'] + '%)');
            $("#span-vacant").html( content_data['all_status'][1]['count'] + '(' + content_data['all_status'][1]['percent'] + '%)');
            $("#span-unavailable").html( content_data['all_status'][2]['count'] + '(' + content_data['all_status'][2]['percent'] + '%)');
        }
        let isOver = false;
        let cur_tooltip;

        function add_tooltips(content_data) {
            var floor_data = content_data['floors'];
            console.log(floor_data);
            for (i = 0; i < floor_data.length; i++) {
                let each_floor = floor_data[i];
                for (j = 0; j < each_floor.length; j++) {
                    var each_room = each_floor[j]['beds'];
                    for (k = 0; k < each_room.length; k++) {
                        add_tooltip(each_floor[j]["roomNo"], each_room[k]);
                    }
                }
            }

            $(document).on("mouseover", ".tooltip", (event) => {
                isOver = true;
            });

            $(document).on("mouseleave", ".tooltip", (event) => {
                // cur_tooltip.tooltip('hide');
                // setTimeout(function () { $('#myLink').tooltip('hide'); }, 500);
            });

            $('[data-toggle="tooltip"]').on('mouseenter', function () {
                if (cur_tooltip!=null && cur_tooltip[0]==$(this)[0]){
                    return;
                }
                if (cur_tooltip)
                    cur_tooltip.tooltip('hide');
                $(this).tooltip('show');
                cur_tooltip=$(this);
                // setTimeout(function () {cur_tooltip.tooltip('hide'); }, 3000);
            });
        }

        function add_tooltip(room_name , bed_data){
            let t_data = '<tr><th class="text-right">Bed&nbsp;</th><td >' + room_name + '-' + bed_data['bedName'] + '</td></tr><tr><th class="text-right">Status&nbsp;</th><td class="">' + bed_data['status'] + '</td></tr>';
            t_data += '<tr><th class="text-right" style="padding-right:5px;">Change Status</th><td>';
            t_data += '<a class="send-to-server-click"  data="bedid:' + bed_data['id'] + '|csrf-token:{{ csrf_token() }}" url="<?=url("Room@ChangeBedStatus");?>"  warning-button="Continue" loader="true" href="" warning-title="Are you sure?"';
            if (bed_data['status'] =="Vacant")
                t_data += ' warning-message="This Bed status will change to Unavailable.">Set Unavailable';
            else
                t_data += ' warning-message="This Bed status changes to Vacant.">Set Vacant';
            t_data += '</a></td></tr>';

            if(bed_data["user"] != null){
                t_data +='<tr><th class="text-right">Name&nbsp;</th> <td><a href="' + bed_data["user"]["url"] + '" rel="noreferrer" target="_blank">' + bed_data["user"]["name"] + '</a></td> </tr> <tr> <th class="text-right" >Gender&nbsp;</th> <td>' + bed_data["user"]["gender"] + '</td> </tr><tr> <th class="text-right" >Identifier&nbsp;</th> <td>' + bed_data["user"]['identifier'] + '</td> </tr><tr><th>Avatar </th><td>';
                if(bed_data["user"]["avatar"] == ""){
                    t_data += '<img src="<?=url("");?>assets/images/avatar.png" class="user-avatar2"></td></tr>';
                }
                else{
                    t_data +='<img src="<?=url("");?>uploads/avatar/' + bed_data["user"]["avatar"] + '" class="user-avatar2"></td></tr>';
                }
            }



            $('#popup-root tbody').html(t_data);
            $(".bed-"+bed_data['id']).tooltip(
                {
                    html: true,
                    title: $('#popup-root').html(),
                    placement:'auto right',
                    trigger: 'manual',
                    delay: { hide: 400}
                });
        }
    </script>
    <script src="<?= url(""); ?>assets/js/room.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    
    <style>
        tbody, td, tfoot, th, thead, tr {
            border: 0 solid;
            border-color: inherit;
        }

        .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            border-top: inherit;
        }
    </style>
</body>

</html>
