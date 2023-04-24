{{ view("includes/head", $data); }}
<?php
$t = time();

if ($_SESSION["google_login"]) {
    if ($_SESSION["google_login_expire"] < $t)
        $_SESSION["google_login"] = false;
}
?>

<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
            <br>
            <div class="row ">
                <div class="col-md-3">
                    <a class="btn btn-primary" href="<?= url("Youtube@getGroup"); ?>" >返回集团</a>
                </div>
            </div>
            <br>
            <h3>编辑集团</h3>
            <p>您可以通过搜索设置精彩视频。。</p>
        </div>
        <div class="row margin-0">
            <div class="col-md-11 bg-white" style="padding:20px 10px">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>集团名字 : </label>
                            <span class="color-red">{{$youtube_group->name}}</span>
                        </div>
                    </div>
                </div>

                <div class="light-card table-responsive p-b-3em">
                    <table class="table display page-list" id="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>视频链接</th>
                                <th>标题</th>
                                <th class="text-right" style="width:320px;">行动</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if ( count($youtube_videos) > 0 )
                            @foreach ( $youtube_videos as $index => $each_video )
                            <tr>
                                <td>{{$index + 1 }}</td>
                                <td>
                                    <a href="https://www.youtube.com/watch?v={{$each_video->video_id}}" target="_blank">
                                        <img src="{{$each_video->video_thumbnails}}" width="160">
                                    </a>
                                </td>
                                <td><strong>{{$each_video->video_title}}</strong></td>

                                <td class="text-right">
                                    <a class="btn btn-success send-to-server-click" data="user_id:{{$user->id}}|video_id:{{$each_video->video_id}}|csrf-token:{{ csrf_token() }}" url="<?= url("Youtube@unsetVideoDB"); ?>" warning-title="你确定吗？" warning-message="此视频将从突出显示中删除。" warning-button="Continue" loader="true">删除突出显示</a>
                                </td>
                            </tr>
                            @endforeach

                            @else
                            <tr>
                                <td colspan="9" class="text-center">这里是空的</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <br>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>搜索 Youtube 视频以设置突出显示</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="q">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-success" type="button" onclick="search_video()">搜索</button>
                        </div>
                    </div>
                    <br>

                    <form class="simcy-form" action="<?= url("Youtube@chooseVideoDB"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                        <input type="hidden" id="highlight-video-json" value="{{json_encode($youtube_videos)}}" />
                        <div class="search-result light-card table-responsive p-b-3em result-html">

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>


    <!-- footer -->
    {{ view("includes/footer"); }}

    <?php $url_para = explode("/", $_SERVER['REQUEST_URI']); ?>
    <script>
        var controller_name = "<?php echo $url_para[1] ?>";
        if (controller_name == "youtube") {
            $(".yt-submenu").addClass("pushy-submenu-open");
        }

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

        function search_video() {
            $.ajax({
                url: '/youtube/searchVideoAjax', // sending ajax request to this url
                type: 'post',
                data: {
                    'q': $("#q").val(),
                    'csrf-token': '{{csrf_token()}}',
                    'user_id': '{{$user->id}}',
                    'group_id': '{{$youtube_group->id}}'
                },
                dataType: "json",
                success: function(response) {
                    show_video_list(response);
                }
            });
        }

        function show_video_list(video_list) {
            var result_html = "";
            $(".result-html").html('');
            for (i = 0; i < video_list.length; i++) {
                result_html += '<div style="display: flex">'
                result_html += '    <div style="display: flex">'
                result_html += '        <input type="checkbox" name="video_infor[]" value="' + video_list[i]['json'] + '" style="margin: 0">'
                result_html += '    </div>'
                result_html += '    <div class="col-md-3">'
                result_html += '        <a href="https://www.youtube.com/watch?v=' + video_list[i]['video_id'] + '" target="_blank">'
                result_html += '        <img src="' + video_list[i]['video_thumbnails'] + '" width="160">'
                result_html += '        </a>'
                result_html += '    </div>'
                result_html += '    <div>'
                result_html += video_list[i]['video_title']
                result_html += '    </div>'
                result_html += '</div><br>'

            }
            result_html += '<button type="submit" class="btn btn-primary" style="float:right;">设置亮点</button>';
            $(".result-html").append(result_html);

        }

        function check_video_set(youtube_videos_array, t_video_id) {
            for (j = 0; j < youtube_videos_array.length; j++) {
                if (youtube_videos_array[j]['video_id'] == t_video_id)
                    return true;
            }
            return false;
        }
    </script>


</body>

</html>