<?php //include("../includes/head.php") ?>
{{ view("includes/head", $data); }}
<body>
<!-- header start -->
{{ view("includes/header", $data); }}

<!-- sidebar -->
{{ view("includes/sidebar", $data); }}

<div class="content">
    <div class="page-title">
        <h3>Report</h3>
    </div>
    <div class="row">
        <div class="col-md-12 light-card">
            <form class="simcy-form" id="create_report_form" action="<?=url("Report@createReport");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
                <div class="col-md-6 row-d">
                    <label>Template</label>
                    <select class="form-control valid col-md-6" name="template_id">
                        @foreach ( $reports_template as $each_template )
                        <option value="{{$each_template->id}}">{{$each_template->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 row-d">
                    <label>Name</label>
                    <input type="text" class="form-control valid col-md-6" name="name" placeholder="Name" value="" required="" aria-invalid="false">
                </div>

                <div class="col-md-6 row-d">
                    <label>Description</label>
                    <textarea class="form-control valid" name="description" required="" rows="9" ></textarea>
                </div>

                <div class="col-md-6 row-d">
                    <button type="button" class="btn btn-primary" style="float:right" id="create_bt">
                        <i class="ion-plus-round"></i>
                        New Custom Report
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="row" style="margin-top:20px;">
        <div class="light-card col-md-12 table-responsive" style="margin-right: 10px;">
            <table class="table display companies-list" id="data-table">
                <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Created at</th>
                </tr>
                </thead>
                <tbody>
                @if ( count($reports) > 0 )
                @foreach ( $reports as $index => $report )
                <tr class="c-p" >
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td onclick="redirect_report('{{$report["report"]->id}}')">{{$report["template"]->name}}</td>
                    <td onclick="redirect_report('{{$report["report"]->id}}')">{{$report["report"]->name}}</td>
                    <td onclick="redirect_report('{{$report["report"]->id}}')" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 450px;">
                    {{$report["report"]->description}}</td>
                    <td onclick="redirect_report('{{$report["report"]->id}}')" style="padding-right: 50px;">{{$report["report"]->created_at}}</td>
                    <td class="text-center">
                        <div class="dropdown">
                            <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>
                            <ul class="dropdown-menu" role="menu">
                                <li role="presentation">
                                    <a class="fetch-display-click" data="reportid:{{ $report['report']->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Report@updateview");?>" holder=".update-holder" modal="#update" href="">Edit</a>
                                    <a class="send-to-server-click"  data="reportid:{{ $report['report']->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Report@delete");?>" warning-title="Are you sure?" warning-message="This Report will be deleted." warning-button="Continue" loader="true" href="">Delete</a>
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
    var controller_name = "<?php echo $url_para[1] ?>";
    if(controller_name == "report"){
        $(".pushy-submenu").addClass("pushy-submenu-open");
    }
    $(document).ready(function() {

        $( "#create_bt" ).click(function() {
            $( "#create_report_form" ).submit();
        });
    });

    function redirect_report(r_id){
        location.replace("/report/browse/" + r_id);
    }

</script>
</body>

</html>

