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

            <h3>Twitter 帐户</h3>
            @if(!$_SESSION['twitter_login'])
            <div class=" page-actions lower">
                <a href="<?php echo $authUrl?> {{$user->id}}" style="color:#212121;cursor: pointer;">
                    <img src="<?= url("/"); ?>assets/images/twitter_icon.png" style="height:22px;margin-top: -8px;">
                    <span style="font-size: 19px; line-height: 7px; color:deepskyblue">Twitter 登录</span>
                </a>
            </div>
            <br>
            <p>请使用 Twitter 帐户登录。</p>
            @else
            <p style="color:red">现在您已登录 Twitter！</p>
            <p>您登录Twitter帐户<span style="color:deepskyblue">@{{$twitter_oauth->twitter_name}}</span></p>
            @endif

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