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
            <h3>Facebook 发布帖子</h3>
            <p>您可以将帖子发布到您设置的 Facebook 页面。</p>
            <p style="color:orangered">请检查您设置了哪些页面。</p>
        </div>
        <div class="row margin-0">
            <div class="col-md-11 bg-white" style="padding:20px 10px">
                <form class="simcy-form" action="<?= url("Facebook@publishPostDB"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                    <div class="form-group">

                        <div class="row">
                            <div class="col-md-6">
                                <label>发布内容</label><br>
                                <textarea class="form-control" style="width: 700px; height: 300px;" maxlength="330" data-parsley-required="true" name="message"></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">发布帖子</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

    <?php $url_para = explode("/", $_SERVER['REQUEST_URI']); ?>
    <script>
        var controller_name = "<?php echo $url_para[1] ?>";

        if (controller_name == "facebook") {
            $(".fb-submenu").addClass("pushy-submenu-open");
        }
    </script>
</body>

</html>