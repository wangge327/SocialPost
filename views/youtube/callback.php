{{ view("includes/head", $data); }}
// Google passes a parameter 'code' in the Redirect Url
<?php
if (isset($_GET['code'])) {
    try {
        /*
        $gapi = new GoogleLoginApi();

        // Get the access token 
        $data = $gapi->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $_GET['code']);
        print_r($data['access_token']);

        $uapi = new YoutubeApi();
        $uapi->sendComment(API_KEY, $data['access_token'], "PDDoE3QiyF8");

        // Get user information
        $user_info = $gapi->GetUserProfileInfo(API_KEY, $data['access_token']);
*/
        echo '<pre>';
        //print_r($user_info);
        echo '</pre>';

        // Now that the user is logged in you may want to start some session variables
        $_SESSION['logged_in'] = 1;

        // You may now want to redirect the user to the home page of your website
        // header('Location: home.php');
    } catch (Exception $e) {
        echo $e->getMessage();
        exit();
    }
}
?>

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

        <!--
        <div class="row margin-0">
            <div class="col-md-12" style="padding:0">
                <form class="simcy-form" action="<?= url("Facebook@addFbAccount"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="fb_id" value="<?= $response_array->id ?>">
                    <input type="hidden" name="fb_name" value="<?= $response_array->name ?>">
                    <input type="hidden" name="fb_email" value="<?= $response_array->email ?>">
                    <input type="hidden" name="fb_access_token" value="<?= $access_token_string ?>">
                    <input type="hidden" name="fb_long_lived_access_token" value="<?= $access_token_string ?>">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Facebook ID : </label>
                                <span class="color-red"><?= $response_array->id ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Facebook名称 : </label>
                                <span class="color-red"><?= $response_array->name ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Facebook邮件 : </label>
                                <span class="color-red"><?= $response_array->email ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>

-->
    </div>


    <!-- footer -->
    {{ view("includes/footer"); }}

    <?php $url_para = explode("/", $_SERVER['REQUEST_URI']); ?>
    <script>
        var controller_name = "<?php echo $url_para[1] ?>";
        if (controller_name == "facebook") {
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