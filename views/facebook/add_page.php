{{ view("includes/head", $data); }}
<?php
include("config.php"); // All settings in the $config-Object

if (isset($_GET['state'])) {

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
        <p>Please select one of the many pages of your registered facebook account to send posts.</p>
        <!--<p>请选择您注册的 Facebook 帐户的多个页面之一来因为发送帖子。</p>-->
    </div>
    <div class="row margin-0">
        <div class="col-md-11 bg-white" style="padding:20px 10px">
            <form class="simcy-form" action="<?= url("Facebook@addFbAccount"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" >
                <input type="hidden" name="fb_id" value="{{$fb_user->id}}">

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Facebook Name : </label>
                            <span class="color-red">{{$fb_user->fb_name}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Facebook Email : </label>
                            <span class="color-red">{{$fb_user->fb_email}}</span>
                        </div>
                    </div>
                </div>

                <div class="light-card table-responsive p-b-3em">
                    <table class="table display page-list" id="data-table">
                        <thead>
                        <tr>
                            <th class="text-center">Page Name</th>
                            <th class="text-center">Page Intro</th>
                            <th>Status</th>
                            <th class="text-center w-70">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @if ( count($fb_pages) > 0 )

                        <tr>
                            <td><strong>Intrepid Travel</strong> </td>
                            <td>We are deeply saddened by the news of the earthquakes that shook southern Türkiye</td>
                            <td>
                                <span class="label label-success">Set</span>
                            </td>

                            <td class="text-center">
                                <a class="btn btn-success send-to-server-click" data="page_id:123|csrf-token:{{ csrf_token() }}" url="" warning-title="Are you sure?" warning-message="This Page will be unset." warning-button="Continue" loader="true" >Unset</a>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>Salone Monet</strong> </td>
                            <td>Zoom in on my shoes!!!</td>
                            <td>
                                <span class="label label-warning">Unset</span>
                            </td>

                            <td class="text-center">
                                <a class="btn btn-primary send-to-server-click" data="page_id:123|csrf-token:{{ csrf_token() }}" url="" warning-title="Set Page" warning-message="This Page will be set for sending posts." warning-button="Set" >Set</a>
                            </td>
                        </tr>

                        @else
                        <tr>
                            <td colspan="9" class="text-center">It's empty here</td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
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