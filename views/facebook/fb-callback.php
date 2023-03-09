{{ view("includes/head", $data); }}
<?php
include("config.php"); // All settings in the $config-Object

if (isset($_GET['access_token'])) {

    $access_token_string = $_GET['access_token'];

    $user_details = "https://graph.facebook.com/me?fields=email,name,id,birthday,gender&access_token=" . $access_token_string;

    $response = file_get_contents($user_details);
    $response_array = json_decode($response);
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