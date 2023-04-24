{{ view("includes/head", $data); }}

<?php
$authUrl = env("APP_URL") . "/social/twitter/index.php?uid=";
?>

<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        @if($_SESSION["google_login"])
        <div class="page-title">
            <h3>Youtube 发送评论</h3>
            <p>您可以通过选择集团发送评论。</p>
            <p style="color:orangered">您不能一次发送关于100个Youtube帐户的评论。。</p>
        </div>
        <div class="row margin-0">
            <div class="col-md-11 bg-white" style="padding:20px 10px">
                <form class="simcy-form" action="<?= url("Youtube@sendCommentDB"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>集团名字</label><br>
                                <select class="form-control select2" name="group_id">
                                    @foreach($youtube_group as $each_group)
                                    <option value="{{$each_group->id}}">{{$each_group->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>评论内容</label><br>
                                <textarea class="form-control" style="width: 700px; height: 300px;" maxlength="330" data-parsley-required="true" name="comment"></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">发送评论</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @else

        <div class="page-title">
            <br>
            <p style="color:red">没有登录Youtube</p>
            <span>登录信息丢失。 请登录 Youtube 发送评论。</span><br><br>
            <a class="btn btn-primary" href="{{env('APP_URL')}}/youtube">Youtube登录</a>
        </div>
        @endif
    </div>
    <!-- footer -->
    {{ view("includes/footer"); }}

    <?php $url_para = explode("/", $_SERVER['REQUEST_URI']); ?>
    <script>
        var controller_name = "<?php echo $url_para[1] ?>";

        if (controller_name == "youtube") {
            $(".yt-submenu").addClass("pushy-submenu-open");
        }
    </script>
</body>

</html>