{{ view("includes/head", $data); }}
<?php
$redirect_url = env("APP_URL") . '/youtube/callback';

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

            <h3>Youtubek Account</h3>
            @if($_SESSION["google_login"] != true)
            <div class=" page-actions lower">
                <a onclick="oauthSignIn()" style="color:#212121;cursor: pointer;">
                    <img src="<?= url("/"); ?>assets/images/yt_icon_rgb.png" style="height:22px;margin-top: -8px;">
                    <span style="font-size: 19px; line-height: 7px;">YouTube</span>
                </a>
            </div>
            <br>
            <p>Please login with youtube account.</p>
            @else
            <p style="color:red">Now you logged into Youtube!</p>
            @endif

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

        function oauthSignIn() {
            // Google's OAuth 2.0 endpoint for requesting an access token
            var oauth2Endpoint = 'https://accounts.google.com/o/oauth2/v2/auth';

            // Create <form> element to submit parameters to OAuth 2.0 endpoint.
            var form = document.createElement('form');
            form.setAttribute('method', 'GET'); // Send as a GET request.
            form.setAttribute('action', oauth2Endpoint);

            // Parameters to pass to OAuth 2.0 endpoint.
            var params = {
                'client_id': '<?= env("GOOGLE_CLIENT_ID") ?>',
                'redirect_uri': '<?= $redirect_url ?>',
                'response_type': 'token',
                'scope': 'https://www.googleapis.com/auth/youtube.force-ssl',
                'include_granted_scopes': 'true',
                'state': 'pass-through'
            };

            // Add form parameters as hidden input values.
            for (var p in params) {
                var input = document.createElement('input');
                input.setAttribute('type', 'hidden');
                input.setAttribute('name', p);
                input.setAttribute('value', params[p]);
                form.appendChild(input);
            }

            // Add form to page and submit it to open the OAuth 2.0 endpoint.
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>

</html>