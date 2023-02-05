{{ view("includes/head", $data); }}
<?php
include("config.php"); // All settings in the $config-Object

if (isset($_GET['state'])) {
    $helper->getPersistentDataHandler()->set('state', $_GET['state']);

    // 사용자 액세스 토큰 획득
    try {
        $accessToken = $helper->getAccessToken();
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an erro: ' . $e->getMessage();
        exit;
    }

//각종 에러 처리
    if (!isset($accessToken)) {
        if ($helper->getError()) {
            header('HTTP/1.1 401 Unauthorized');
            echo "Error: " . $helper->getError() . "\n";
            echo "Error Code: " . $helper->getErrorCode() . "\n";
            echo "Error Reason: " . $helper->getErrorReason() . "\n";
            echo "Error Description: " . $helper->getErrorDescription() . "\n";
        } else {
            header('HTTP/1.1 400 Bad Request');
            echo 'Bad request';
        }
        exit;
    }

    $access_token_string = $accessToken->getValue();

    $oAuth2Client = $fb->getOAuth2Client();

    $tokenMetadata = $oAuth2Client->debugToken($accessToken);

    $user_details = "https://graph.facebook.com/me?fields=email,name,id,gender&access_token=" . $access_token_string;

    $response = file_get_contents($user_details);
    $response = json_decode($response);

    //-------

    $tokenMetadata->validateAppId(env("FACEBOOK_APP_ID"));
    $tokenMetadata->validateExpiration();

    //장기 토큰 변환
        if (!$accessToken->isLongLived()) {
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                $response = $fb->get('/me', $accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
                exit;
            }
            $graphNode = $response->getGraphNode();
        }

    //변환된 장기 토큰 출력
        $long_lived_access_token_string = $accessToken->getValue();
}
?>
<body>
<!-- header start -->
{{ view("includes/header", $data); }}
<!-- sidebar -->
{{ view("includes/sidebar", $data); }}
<div class="content">
    <div class="page-title">
        <h3>Facebook账号</h3>
        <p>刚登入Facebook帐户</p>
    </div>
    <div class="row margin-0">
        <div class="col-md-12" style="padding:0">
            <form class="simcy-form" action="<?= url("Facebook@addFbAccount"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                <input type="hidden" name="fb_id" value="<?= $response->id ?>">
                <input type="hidden" name="fb_name" value="<?= $response->name ?>">
                <input type="hidden" name="fb_email" value="<?= $response->email ?>">
                <input type="hidden" name="fb_access_token" value="<?= $access_token_string ?>">
                <input type="hidden" name="fb_long_lived_access_token" value="<?= $long_lived_access_token_string ?>">

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Facebook名称 : </label>
                            <span class="color-red"><?= $response->name ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Facebook邮件 : </label>
                            <span class="color-red"><?= $response->email ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">保存</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>


<!-- footer -->
{{ view("includes/footer"); }}

<?php   $url_para = explode("/", $_SERVER['REQUEST_URI']); ?>
<script>
    var controller_name = "<?php echo $url_para[1] ?>";
    if(controller_name == "facebook"){
        $(".fb-submenu").addClass("pushy-submenu-open");
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
</script>


</body>

</html>