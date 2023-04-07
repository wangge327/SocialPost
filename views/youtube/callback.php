{{ view("includes/head", $data); }}

<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
            <h3>Youtube账号</h3>
            <p>刚登入Youtube帐户</p>
        </div>
    </div>


    <!-- footer -->
    {{ view("includes/footer"); }}

    <?php $url_para = explode("/", $_SERVER['REQUEST_URI']); ?>
    <script>
        var controller_name = "<?php echo $url_para[1] ?>";
        if (controller_name == "") {
            $(".fb-submenu").addClass("pushy-submenu-open");
        }

        var url = window.location.hash.replace("#", "");

        $.ajax({
            url: '/youtube/callbackajax', // sending ajax request to this url
            type: 'post',
            data: {
                'callback_url': url,
                'csrf-token': '{{csrf_token()}}'
            },
            success: function(response) {
                console.log(response);
                if (response == 'success') {
                    alert('Youtube登录成功!!!');
                    window.location.href = "<?= url("Youtube@get"); ?>";
                }
                if (response == 'error') {
                    alert('Youtube登录 Error!!!');
                    window.location.href = "<?= url("Youtube@get"); ?>";
                }
            }
        });
    </script>


</body>

</html>