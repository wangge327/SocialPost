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
        <div class="page-title">
            <h3>Twitter发送推文管理</h3>
            <p>您可以将推文发送到 Twitter。</p>
            <p style="color:orangered">不要发送太多推文。</p>
        </div>
        <div class="row margin-0">
            <div class="col-md-11 bg-white" style="padding:20px 10px">
                <form class="simcy-form" action="<?= url("Twitter@sendTweetDB"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                    <div class="form-group">
                        <div class="row">
                            @if(!$_SESSION['twitter_login'])
                            <div class="col-md-12">
                                <label style="color:red">您没有登录 Twitter 帐户</label>
                            </div>
                            @else
                            <div class="col-md-12">
                                <label>Twitter ID : </label>
                                <span class="color-red">@{{$twitter_oauth->twitter_name}}</span>
                            </div>
                            @endif
                        </div>
                        @if(!$_SESSION['twitter_login'])
                        <div class="row">
                            <div class="col-md-12">
                                <a href="<?= url("Twitter@get"); ?>">请重定向到使用 Twitter 帐户登录。 </a>
                            </div>
                        </div>
                        @else
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label>推文内容</label><br>
                                <textarea class="form-control" style="width: 700px; height: 300px;" maxlength="330" data-parsley-required="true" name="tweet"></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">发送推文管理</button>
                            </div>
                        </div>

                        @endif
                    </div>
                </form>
            </div>
        </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

    <?php $url_para = explode("/", $_SERVER['REQUEST_URI']); ?>
    <script>
        var controller_name = "<?php echo $url_para[1] ?>";

        if (controller_name == "twitter") {
            $(".tw-submenu").addClass("pushy-submenu-open");
        }
    </script>
</body>

</html>