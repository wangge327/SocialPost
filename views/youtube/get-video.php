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
            <h3>Youtube视频设置</h3>
            <p>Please search and set Highlight to send comment to youtube.</p>
        </div>
        <div class="row margin-0">
            <div class="col-md-11 bg-white" style="padding:20px 10px">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Your Highlight Videos : </label>
                            <span class="color-red">{{count($youtube_videos)}}</span>
                        </div>
                    </div>
                </div>

                <div class="light-card table-responsive p-b-3em">
                    <table class="table display page-list" id="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Page ID</th>
                                <th>Title</th>
                                <th class="text-center" style="width:320px;">Action</th>
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

                                <td class="text-center">
                                    <a class="btn btn-success send-to-server-click" data="user_id:{{$user->id}}|video_id:{{$each_video->video_id}}|csrf-token:{{ csrf_token() }}" url="<?= url("Youtube@unsetVideoDB"); ?>" warning-title="Are you sure?" warning-message="This Video will be Unset." warning-button="Continue" loader="true">Unset Highlight</a>
                                    <a class="fetch-display-click btn btn-primary" data="user_id:{{$user->id}}|video_id:{{$each_video->video_id}}|csrf-token:{{ csrf_token() }}" url="<?= url("Youtube@sendCommentView"); ?>" holder=".update-holder" modal="#send-comment" style="margin-left:10px">Send Comment</a>
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

                <br>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Search Youtube videos for setting</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="q">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-success" type="button" onclick="search_video()">Search</button>
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

    <!-- Send Comment Modal -->
    <div class="modal fade" id="send-comment" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Send Comment</h4>
                </div>
                <form class="update-holder simcy-form" id="send-comment-form" action="/youtube/send_comment" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box">
                        <div class="circle-loader"></div>
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
                    'user_id': '{{$user->id}}'
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
            result_html += '<button type="submit" class="btn btn-primary" style="float:right;">Set Highlight</button>';
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