{{ view("includes/head", $data); }}
<?php
include("config.php"); // All settings in the $config-Object
?>

<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
            <h3>Facebook Pages</h3>
            <p>您将使用 facebook api 自动获取已注册 facebook 帐户的所有页面。</p>
            <!--<p>You will get all pages of registered facebook account automatically using facebook api.</p>-->
            <p>如果您想发布帖子到页面，请点击页面列表中的“发布帖子”按钮。</p>
            <!--<p>Please click "Publish Post" button from page lists if you want to publish post to page.</p>-->
        </div>
        <div class="row margin-0">
            <div class="col-md-11 bg-white" style="padding:20px 10px">
                <form class="simcy-form" action="<?= url("Facebook@addFbAccount"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">


                    <div class="form-group">
                        <div class="row">
                            @if(empty($fb_user))
                            <div class="col-md-12">
                                <label style="color:red">您没有登录 Facebook 帐户</label>
                                <!--<label style="color:red">You didn't register Facebook account</label>-->
                            </div>
                            @else
                            <div class="col-md-12">
                                <label>Facebook ID : </label>
                                <span class="color-red">{{$fb_user->fb_id}}</span>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            @if(empty($fb_user))
                            <div class="col-md-12">
                                <a href="<?= url("Facebook@get"); ?>">请重定向到使用 Facebook 帐户登录。 </a>
                                <!--<a href="<?= url("Facebook@get"); ?>">Please redirect to login with Facebook account. </a>-->
                            </div>
                            @else
                            <div class="col-md-12">
                                <label>Facebook 姓名 : </label>
                                <span class="color-red">{{$fb_user->fb_name}}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="light-card table-responsive p-b-3em">
                        <table class="table display page-list" id="data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Page ID</th>
                                    <th>Page 姓名</th>
                                    <th class="text-center w-70">行动</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if ( count($fb_pages_api) > 0 )
                                @foreach ( $fb_pages_api as $index => $each_fb_page )
                                <tr>
                                    <td>{{$index + 1 }}</td>
                                    <td>{{$each_fb_page->id}}</td>
                                    <td><strong>{{$each_fb_page->name}}</strong></td>

                                    <td class="text-center">
                                        <a class="fetch-display-click btn btn-success" data="user_id:{{$user->id}}|fb_id:{{$fb_user->fb_id}}|page_id:{{$each_fb_page->id}}|page_name:{{$each_fb_page->name}}|page_access_token:{{$each_fb_page->page_token}}|csrf-token:{{ csrf_token() }}" url="<?= url("Facebook@publishPostView"); ?>" holder=".update-holder" modal="#publish-post" href="">发布帖子</a>
                                    </td>
                                </tr>
                                @endforeach

                                @else
                                <tr>
                                    <td colspan="9" class="text-center">这里是空的</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Publish Post Modal -->
    <div class="modal fade" id="publish-post" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">发布帖子</h4>
                </div>
                <form class="update-holder simcy-form" id="update-customer-form" action="<?= url("Posting@publishPostFacebook"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box">
                        <div class="circle-loader"></div>
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