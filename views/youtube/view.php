{{ view("includes/head", $data); }}
<?php
$redirect_url = env("APP_URL") . '/youtube/callback';
$login_url = 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode('https://www.googleapis.com/auth/youtube.force-ssl') . '&redirect_uri=' . $redirect_url . '&response_type=code&client_id=' . env("GOOGLE_CLIENT_ID") . '&access_type=online';

?>

<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">

            <h3>Youtubek账号</h3>
            <div class=" page-actions lower">
                <a class="btn btn-primary" href="<?= $login_url ?>">Login with Google</a>
            </div>
            <br>
            <p>所有添加的 Facebook 帐户列表</p>
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
    </script>
</body>

</html>