<?php //include("../includes/head.php") ?>
{{ view("includes/head", $data); }}
<body>
{{ view("includes/header", $data); }}
{{ view("includes/sidebar", $data); }}

<div class="content">
    <div class="page-title">
        <h3>{{$report->name}} <span style="font-size: 18px;" >(Template : {{$report_template->name}})</span>
            <a class="fetch-display-click btn btn-success" data="reportid:{{ $report->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Report@updateview");?>" holder=".update-holder" modal="#update" href="">Edit</a>
        </h3>
        <div class="report-cont-top">
            <div class="top-title"></div>
            <div class="top-desc">
                <br>
                <span>{{$report->description}}</span>
                <br>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 light-card">
                <button class="btn btn-primary" data-toggle="modal" data-target="#columns_modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus-round"></i>Columns</button>

                <button class="btn btn-primary" data-toggle="modal" data-target="#total_modal" data-backdrop="static" data-keyboard="false" style="margin-left: 20px"><i class="ion-plus-round"></i>Add Total</button>

                <button class="btn btn-primary" data-toggle="modal" data-target="#filter_modal" data-backdrop="static" data-keyboard="false" style="margin-left: 20px"><i class="ion-plus-round"></i>Filters</button>

                <div class="top-desc-right" style="float: right">
                    <form method="post" action="<?= url("Report@exportExcel"); ?>" >
                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
                        <input type="hidden" name="reportid" value="{{ $report->id }}" />
                        <button class="btn btn-success" name="action" value="export"><i class="ion-code-download"></i>
                            Export to Excel
                        </button>
                    </form>
                </div>
        </div>
    </div>
    <div class="row" style="margin-top:20px;">
        <div class="light-card col-md-12 table-responsive" style="margin-right: 10px;">
            @if(!empty($total_value))
            <div class="report-cont-bottom">
            @foreach($total_value as $each_value)
                <div class="each-field">
                    <p>{{$each_value['title']}}</p>
                    <span>${{$each_value['value']}}</span>
                </div>
            @endforeach
            </div>
            @endif

            <div class="custom-filter">
                <label>
                    Display Per Page:
                    <select id="per-page-select">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                </label>
            </div>

            <table class="table display companies-list" id="data-table" style="font-size: 13px;">
                <thead>
                <tr>
                    <th>No</th>
                    @foreach ( $report_view['report_view_header'] as $each_report_view_header )
                    <th>{{$each_report_view_header}}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach ( $report_view['report_view_content'] as $each_report_view_content )
                <tr>
                    <td>{{ ++$index }}</td>
                    @foreach ( $each_report_view_content as $each_report_view_content_cell )
                    <td>{{$each_report_view_content_cell}}</td>
                    @endforeach
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Columns Modal -->
<div class="modal fade" id="columns_modal" role="dialog">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Colums</h4>
            </div>
            <form class="update-holder simcy-form" id="update-report-form" action="<?=url("Report@updateColumns");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                <input type="hidden" name="reportid" value="{{ $report->id }}" />
                @foreach ( $report_columns as $each_report_columns )
                <lable style="font-size: 15px;font-weight: bold;">{{$each_report_columns["group_name"]}}</lable>
                <div class="modal-body">
                    <div class="form-group" style="margin-bottom: 0">
                        <div class="row">
                            @foreach ( $each_report_columns["report_columns_meta"] as $report_columns_meta )
                            <div class="m-item m-b-l">
                                <input type="checkbox" name="{{$report_columns_meta->id}}"
                                @if(!empty($report_columns_meta_id_array))
                                @if (in_array($report_columns_meta->id, $report_columns_meta_id_array))
                                       checked="checked"
                                @endif
                                @endif
                                >
                                <label>{{$report_columns_meta->name}}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div><br/>
                @endforeach

                <button type="submit" class="btn btn-primary" style="float: right;margin-top: 20px;">Save</button>
            </form>
        </div>

    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filter_modal" role="dialog" >
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Filters</h4>
            </div>
            <form id="report_filter_form" class="simcy-form" action="<?=url("Report@reportFilter");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                <input type="hidden" id="filter-json" name="filter-json" value="" />
                <input type="hidden" name="reportid" value="{{ $report->id }}" />
                <?php include 'report_filter.php';?>

                <button type="submit" class="btn btn-primary" style="float: right;margin-top: 20px;">Set Filter</button>
            </form>
        </div>
    </div>

</div>

<!-- Total Modal -->
<div class="modal fade" id="total_modal" role="dialog">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Set Total</h4>
            </div>
            <form class="update-holder simcy-form" id="update-report-form" action="<?=url("Report@reportTotal");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                <input type="hidden" name="reportid" value="{{ $report->id }}" />
                <lable style="font-size: 15px;font-weight: bold;margin-bottom:20px;">You can choose Total Rules</lable>

                <?php include 'report_total.php';?>
                <button type="submit" class="btn btn-primary" style="float: right;margin-top: 20px;">Save</button>
            </form>
        </div>
    </div>
</div>

<!-- Update Report Modal -->
<div class="modal fade" id="update" role="dialog">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Report </h4>
            </div>
            <form class="update-holder simcy-form" id="update-report-form" action="<?=url("Report@update");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <div class="loader-box"><div class="circle-loader"></div></div>
            </form>
        </div>

    </div>
</div>

{{ view("includes/footer"); }}

<?php   $url_para = explode("/", $_SERVER['REQUEST_URI']); ?>
<script>
    let filters={{json_encode($filters)}};
    let current_filter_rules={{json_encode($current_filter_rules)}};
    let current_filter_total={{json_encode($current_filter_total)}};
    var controller_name = "<?php echo $url_para[1] ?>";
    if(controller_name == "report"){
        $(".pushy-submenu").addClass("pushy-submenu-open");
    }
    $(document).ready(function() {
        $('#data-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'pdfHtml5'
            ]
        });
        $(".dataTables_filter").append($(".custom-filter"));

        $('#per-page-select').on('change',function(){
            var selectedValue = $(this).val();
            $('#data-table').DataTable().page.len(selectedValue).draw();;
        });
    });

</script>
<script src="<?= url(""); ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?= url(""); ?>assets/js/report.js"></script>
</body>
</html>

