{{ view("includes/head", $data); }}

<body>

    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}

    <div class="content">
        <div class="page-title">
            <h3>集团管理</h3>
            <p>您可以通过设置精彩视频来添加群组和管理群组。</p>
            <div class="row ">
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create" data-backdrop="static" data-keyboard="false"><i class="ion-plus-round"></i>Add Group
                    </button>
                </div>
            </div>
            <br>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em">
                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th class="w-70">No</th>
                                <th class="text-center w-70">集团名字</th>
                                <th class="text-center" style="width: 100px">视频数量</th>
                                <th class="text-right" style="width: 50px">行动</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($youtube_group) > 0 )
                            @foreach ($youtube_group as $index => $each_group )
                            <tr>
                                <td >{{ $index + 1 }}</td>
                                <td class="text-center">
                                    {{$each_group->name}}
                                </td>
                                <td class="text-center" >
                                    {{count($each_group->youtube_videos)}}
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-success" href="<?= url("Youtube@getVideo"); ?>{{ $each_group->id }}">编辑集团</a>
                                    <a class="send-to-server-click btn btn-primary" data="group_id:{{ $each_group->id }}|csrf-token:{{ csrf_token() }}" url="<?= url("Youtube@deleteGroup"); ?>" warning-title="你确定吗？" warning-message="集团数据将被删除。" warning-button="继续" loader="true" href="">删除</a>
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

    <!--Create Group Account-->
    <div class="modal fade" id="create" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">创建集团</h4>
                </div>
                <form class="simcy-form" id="create-customer-form" action="<?= url("Youtube@createGroup"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>请添加集团名</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 ">
                                    <label>集团名字 </label> <label class="color-red">*</label>
                                    <input type="text" class="form-control" name="gname" placeholder="集团名字" data-parsley-required="true">
                                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-primary">创建</button>
                    </div>
                </form>
            </div>

        </div>
    </div>



    <!-- footer -->
    {{ view("includes/footer"); }}

    <?php $url_para = explode("/", $_SERVER['REQUEST_URI']); ?>

    <script>
        var controller_name = "<?php echo $url_para[1] ?>";
        console.log(controller_name);
        if (controller_name == "youtube") {
            $(".yt-submenu").addClass("pushy-submenu-open");
        }

        $(document).ready(function() {

            $('#data-table').DataTable({
                dom: 'Bfrtip',
                "ordering": false,
                buttons: [
                    'excelHtml5',
                    'pdfHtml5',
                ]
            });

        });
    </script>

</body>

</html>